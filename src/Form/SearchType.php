<?php

namespace App\Form;

use \Api;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $api = new Api\Api();
        $genreList = $api->listGenres()->genres;
        $genres = array();
        foreach($genreList as $genre){
            $genres[$genre->name] = $genre->id;
        }
        $builder
            ->add('title')
            ->add('yearStart', DateType::class, [
                    'years' => range(1895,2025)
            ])
            ->add('yearEnd', DateType::class, [
                'years' => range(2025,1895),
            ])
            ->add('genre', ChoiceType::class, [

                'expanded' => true,
                'multiple' => true,
                'choices' => $genres
                
            ])
            ->add('search', SubmitType::class)
            ->add('crew', TextType::class, [
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                // Configure your form options here
        ]);
    }
}
