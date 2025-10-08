<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('content', TextareaType::class, [
                'attr' => ['rows' => 10],
            ])
            /* ->add('publischedAt', null, [
                'widget' => 'single_text',
            ]) */

            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'Category',
                'placeholder' => 'Wählen Sie eine Kategorie aus', // первый пункт
                'required' => false,              // разрешить оставить пустым
                'label_attr' => ['style' => 'margin-right:8px'], // пробел между label и select
                'attr' => ['style' => 'display:inline-block'],   // чтобы стояли в одну линию (если нужно)
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
