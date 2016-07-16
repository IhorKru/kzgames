<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class SubscriberType extends AbstractType {
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder
            ->add('firstname', TextType::class, array(
                'label' => false,
                'required' => true,
                'attr' => array(
                    'placeholder' => 'First Name',
                    'class' => 'form-field-set'
                )))
            ->add('lastname', TextType::class, array(
                'label' => false,
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Last Name',
                    'class' => 'form-field-set'
                )))
            ->add('emailaddress', EmailType::class, array(
                'label' => false,
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Email Address',
                    'pattern'     => '.{2,}', //minlength
                    'class' => 'form-field-set'
                )))  
            ->add('phone', TextType::class, array(
                'label' => false,
                'required' => true,
                'error_bubbling' => true,
                'attr' => array(
                    'placeholder' => 'Mobile Phone',
                    'pattern'     => '.{2,}', //minlength
                    'class' => 'form-field-set'
                )))
            ->add('gender', ChoiceType::class, array(
                'choices' => array('Male' => 1, 'Female' => 2, 'Refuce to answer' => 0),
                'label' => false,
                'required' => true,
                'error_bubbling' => true,
                'placeholder' => 'Gender',
                'attr' => array(
                    'class' => 'form-field-set'
                )))
            ->add('agreeterms', CheckboxType::class, array(
                'label' => '',
                'required' => true))
            ->add('agreeemails', CheckboxType::class, array(
                'label' => '',
                'required' => true))
            ->add('agreepartners', CheckboxType::class, array(
                'label' => '',
                'required' => true))
            ->add('submit', SubmitType::class, array(
                'label' => 'Sign Up', 
                'attr' => array(
                    'class' => 'sub-btn'
                )))
             ;
    }
    
    /**
    * @param OptionsResolverInterface $resolver
    */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Subscriber'
        ));
    }
    /**
     * @return string
     */
    public function getName() {
        return 'subscriber';
    }
}
