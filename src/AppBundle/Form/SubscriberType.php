<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AppBundle\Form\SubscriberOptInType;

class SubscriberType extends AbstractType {
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder
            ->add('phone', TextType::class, ['label' => false, 'required' => true, 'error_bubbling' => true, 'attr' => ['class' => 'input-medium bfh-phone', 'data-format' => '+7 (ddd) ddd-dddd' , 'style' => 'text-align:center']])
            ->add('smscode', TextType::class, ['label' => false, 'required' => true, 'error_bubbling' => true, 'attr' => ['placeholder' => 'SMS Код', 'pattern' => '.{6,}', 'style' => 'text-align:center']])
            ->add('submit', SubmitType::class, ['label' => 'Получить код', 'attr' => ['class' => 'sub-btn' ]]);
    }
    
    /**
    * @param OptionsResolverInterface $resolver
    */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\SubscriberDetails'
        ));
    }
    /**
     * @return string
     */
    public function getName() {
        return 'subscriber';
    }
}
