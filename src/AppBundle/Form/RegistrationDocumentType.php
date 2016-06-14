<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationDocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('listDocument', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', [
                'class' => 'AppBundle\Entity\Document',
                'query_builder' =>  function (EntityRepository $er) {
                    return $er->createQueryBuilder('d')
                        ->orderBy('d.id', 'ASC');
                },
                'choice_label' => 'name'
            ]);
        ;
    }
}