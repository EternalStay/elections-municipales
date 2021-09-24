<?php

namespace App\Form;

use App\Entity\Politicien;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PoliticienEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, array('disabled' => true))
            ->add('sexe')
            ->add('age')
            ->add('mairie')
            ->add('parti')
            ->add('affaires_impliquees')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Politicien::class,
        ]);
    }
}
