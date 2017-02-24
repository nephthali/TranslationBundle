<?php

namespace Cineca\TranslationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslationsType extends AbstractType
{
    private $locales;
    private $data_class;

    public function __construct(array $locales)
    {
        $this->locales = $locales;
    }
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->data_class = isset($options['data_class']) ? $options['data_class'] : null;
        $builder
            ->add('key','textarea',array('label'=>'Text to translate'))
            ->add('translation')
            //This is for Symfony up to 2.8
            #->add('locale',get_class(new ChoiceType()),array())
            //This is before Symfony 2.8
            ->add('locale','choice',array(
                'label' => 'language of translation',
                'choices' => $this->locales)
            )
            ->add('domain','text',array(
                'label' => 'Translation Domain',
                'data' => 'message',
                'disabled' => true)
            )
            #->add('updateAt','date')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            #'data_class' => 'AppBundle\Entity\Translations'
            'data_class' => $this->data_class != null ? $this->data_class : null,
            'choices' => $this->locales,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'cineca_translation';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    /*
    public function setDefaultOptions()
    {
        return null;
    }
    */


}
