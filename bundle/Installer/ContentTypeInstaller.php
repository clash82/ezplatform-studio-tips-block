<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Clash82\EzPlatformStudioTipsBlockBundle\Installer;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\API\Repository\Exceptions\ForbiddenException;
use eZ\Publish\API\Repository\Exceptions\NotFoundException;
use eZ\Publish\API\Repository\Exceptions\UnauthorizedException;
use eZ\Publish\API\Repository\Repository;
use eZ\Publish\API\Repository\UserService;

class ContentTypeInstaller extends Command
{
    /** @var int */
    const ADMIN_USER_ID = 14;

    /** @var \eZ\Publish\API\Repository\Repository */
    private $repository;

    /** @var \eZ\Publish\API\Repository\UserService */
    private $userService;

    /** @var \eZ\Publish\API\Repository\ContentTypeService */
    private $contentTypeService;

    /**
     * @param \eZ\Publish\API\Repository\Repository $repository
     * @param \eZ\Publish\API\Repository\UserService $userService
     * @param \eZ\Publish\API\Repository\ContentTypeService $contentTypeService
     */
    public function __construct(
        Repository $repository,
        UserService $userService,
        ContentTypeService $contentTypeService
    ) {
        $this->repository = $repository;
        $this->userService = $userService;
        $this->contentTypeService = $contentTypeService;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('ezstudio:tips-block:install')
            ->setHelp('Creates a new `Tip` ContentType.')
            ->addOption(
                'name',
                null,
                InputOption::VALUE_OPTIONAL,
                'replaces default ContentType <info>name</info>',
                'Tip'
            )
            ->addOption(
                'identifier',
                null,
                InputOption::VALUE_OPTIONAL,
                'replaces default ContentType <info>identifier</info>',
                'tip'
            )
            ->addOption(
                'group_identifier',
                null,
                InputOption::VALUE_OPTIONAL,
                'replaces default ContentType <info>group_identifier</info>',
                'Content'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $groupIdentifier = $input->getOption('group_identifier');
        $identifier = $input->getOption('identifier');
        $name = $input->getOption('name');

        try {
            $contentTypeGroup = $this->contentTypeService->loadContentTypeGroupByIdentifier($groupIdentifier);
        } catch (NotFoundException $e) {
            $output->writeln(sprintf('ContentType group with identifier %s not found', $groupIdentifier));

            return;
        }

        // create basic ContentType structure
        $contentTypeCreateStruct = $this->contentTypeService->newContentTypeCreateStruct($identifier);
        $contentTypeCreateStruct->mainLanguageCode = 'eng-GB';
        $contentTypeCreateStruct->nameSchema = '<title>';
        $contentTypeCreateStruct->names = [
            'eng-GB' => $identifier,
        ];
        $contentTypeCreateStruct->descriptions = [
            'eng-GB' => 'Tip of the day',
        ];

        // add Title field
        $titleFieldCreateStruct = $this->contentTypeService->newFieldDefinitionCreateStruct('title', 'ezstring');
        $titleFieldCreateStruct->names = [
            'eng-GB' => 'Title',
        ];
        $titleFieldCreateStruct->descriptions = [
            'eng-GB' => 'Title',
        ];
        $titleFieldCreateStruct->fieldGroup = 'content';
        $titleFieldCreateStruct->position = 1;
        $titleFieldCreateStruct->isTranslatable = true;
        $titleFieldCreateStruct->isRequired = true;
        $titleFieldCreateStruct->isSearchable = true;
        $contentTypeCreateStruct->addFieldDefinition($titleFieldCreateStruct);

        // add Description field
        $bodyFieldCreateStruct = $this->contentTypeService->newFieldDefinitionCreateStruct('body', 'ezrichtext');
        $bodyFieldCreateStruct->names = [
            'eng-GB' => 'Body',
        ];
        $bodyFieldCreateStruct->descriptions = [
            'eng-GB' => 'Body',
        ];
        $bodyFieldCreateStruct->fieldGroup = 'content';
        $bodyFieldCreateStruct->position = 2;
        $bodyFieldCreateStruct->isTranslatable = true;
        $bodyFieldCreateStruct->isRequired = true;
        $bodyFieldCreateStruct->isSearchable = true;
        $contentTypeCreateStruct->addFieldDefinition($bodyFieldCreateStruct);

        try {
            $contentTypeDraft = $this->contentTypeService->createContentType($contentTypeCreateStruct, [
                $contentTypeGroup,
            ]);
            $this->contentTypeService->publishContentTypeDraft($contentTypeDraft);

            $output->writeln(sprintf(
                '<info>%s ContentType created with ID %d</info>',
                $name, $contentTypeDraft->id
            ));
        } catch (UnauthorizedException $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));

            return;
        } catch (ForbiddenException $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));

            return;
        }

        $output->writeln(sprintf(
            'Place all your <info>%s</info> content objects into desired folder and then select it as a Parent container in eZ Studio Tips Block options form.',
            $name
        ));
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->repository->setCurrentUser(
            $this->userService->loadUser(self::ADMIN_USER_ID)
        );
    }
}
