<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('matricol', 'Symfony\Component\Form\Extension\Core\Type\TextType')
            ->add('firstName')
            ->add('lastName')
            ->add('phone')
            ->add('cnp')
            ->add('status')
            ->add('personType', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', [
                'class' => 'AppBundle\Entity\PersonType',
                'query_builder' =>  function (EntityRepository $er) {
                    return $er->createQueryBuilder('pt')
                        ->orderBy('pt.id', 'ASC');
                },
                'choice_label' => 'type'
            ]);
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Person'
        ));
    }
}
