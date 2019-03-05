<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformUser\UserSetting\Setting;

use eZ\Publish\Core\MVC\ConfigResolverInterface;
use EzSystems\EzPlatformAdminUi\UserSetting as AdminUiUserSettings;
use EzSystems\EzPlatformUser\Form\DataTransformer\DateTimeFormatTransformer;
use EzSystems\EzPlatformUser\Form\Type\UserSettings\ShortDateTimeFormatType;
use EzSystems\EzPlatformUser\UserSetting\Setting\Value\DateTimeFormat;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ShortDateTimeFormat extends AbstractDateTimeFormat
{
    /** @var \Symfony\Component\Translation\TranslatorInterface */
    private $translator;

    /** @var \eZ\Publish\Core\MVC\ConfigResolverInterface */
    private $configResolver;

    /**
     * @param \EzSystems\EzPlatformUser\UserSetting\Setting\DateTimeFormatSerializer $serializer
     * @param \Symfony\Component\Translation\TranslatorInterface $translator
     * @param \eZ\Publish\Core\MVC\ConfigResolverInterface $configResolver
     */
    public function __construct(
        DateTimeFormatSerializer $serializer,
        TranslatorInterface $translator,
        ConfigResolverInterface $configResolver
    ) {
        parent::__construct($serializer);

        $this->translator = $translator;
        $this->configResolver = $configResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultValue(): string
    {
        $format = $this->configResolver->getParameter('user_preferences.short_datetime_format');

        return $this->serializer->serialize(
            new DateTimeFormat($format['date_format'], $format['time_format'])
        );
    }

    /**
     * {@inheritdoc}
     */
    public function mapFieldForm(
        FormBuilderInterface $formBuilder,
        AdminUiUserSettings\ValueDefinitionInterface $value
    ): FormBuilderInterface {
        $valueForm = $formBuilder->create(
            'value',
            ShortDateTimeFormatType::class,
            [
                'label' => false,
            ]
        );

        $valueForm->addModelTransformer(new DateTimeFormatTransformer($this->serializer));

        return $valueForm;
    }

    /**
     * {@inheritdoc}
     */
    protected function getTranslatedName(): string
    {
        return $this->translator->trans(
            /** @Desc("Short Date & Time format") */
            'settings.short_datetime_format.value.title',
            [],
            'user_settings'
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getTranslatedDescription(): string
    {
        return $this->translator->trans(
            /** @Desc("Format for displaying Date & Time") */
            'settings.short_datetime_format.value.description',
            [],
            'user_settings'
        );
    }
}