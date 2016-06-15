<?php

namespace AppBundle\Form;


use AppBundle\Entity\PersonType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeetingLineType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('activitate', 'Symfony\Component\Form\Extension\Core\Type\TextType')
            ->add('deadline', 'Symfony\Component\Form\Extension\Core\Type\DateType')
            ->add('completionDate', 'Symfony\Component\Form\Extension\Core\Type\DateType')
            ->add('signature', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
                'choices' => [
                    'da' => 1,
                    'nu' => 0
                ]
            ])->add('personMeet', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', [
                'class' => 'AppBundle\Entity\Person',
                'query_builder' =>  function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->where('p.personType = :personType')->setParameter('personType', PersonType::PERSON_TYPE_STUDENT)
                        ->orderBy('p.id', 'ASC');
                },
                'choice_label' => 'name'
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'AppBundle\Entity\MeetingLine'
            ]
        );
    }
}