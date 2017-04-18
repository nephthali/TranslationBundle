<?php

namespace Cineca\TranslationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

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
            ->add('key',null,array(
                'label'=>'Text to translate',
                'constraints' => array(
                    new NotBlank(array('message' => 'Text to translate is required')),
                    new Regex(array(
                        'pattern' => "/[\\/\\\\\|\?&\"$]/",
                        'match' => false,
                        'message' => "Invalid character inserted"
                        ))
                )
            ))
            ->add('translation',null,array(
                'constraints' => array(new NotBlank(arraY('message' => 'Translation Text is required')))
            ))
            //This is for Symfony up to 2.8
            #->add('locale',get_class(new ChoiceType()),array())
            //This is before Symfony 2.8
            ->add('locale','choice',array(
                'label' => 'language of translation',
                'choices' => $this->locales,
                'constraints' => array(
                    new NotBlank(array('message' => 'Locale language is required')),
                    new Regex(array(
                        'pattern' => "/[\\/\\\\\|\?&\"$]/",
                        'match' => false,
                        'message' => "Invalid character inserted"
                        ))
                )
            ))
            ->add('domain',null,array(
                'label' => 'Translation Domain',
                //'empty_data' => 'messages',
                'disabled' => false,
                'constraints' => array(
                    new NotBlank(array('message' => 'You have to define the domain of translation. ex: messages')),
                    new Regex(array(
                        'pattern' => "/[\\/\\\\\|\?&\"$]/",
                        'match' => false,
                        'message' => "Invalid character inserted"
                        )))
            ))
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

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'attr'=>array('novalidate'=>'novalidate')
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
