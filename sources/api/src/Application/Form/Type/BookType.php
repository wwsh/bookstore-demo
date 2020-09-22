<?php

namespace Ddd\Application\Form\Type;

use Ddd\Application\Form\DataTransformer\CategoryToNameTransformer;
use Ddd\Domain\Entity\Book;
use Ddd\Domain\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    private CategoryToNameTransformer $transformer;

    public function __construct(CategoryToNameTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
//            ->add(
//                'categories',
//                CollectionType::class,
//                [
//                    'entry_type' => TextType::class,
//                    'empty_data' => function (FormInterface $form) {
//                        return $form->getExtraData();
//                    },
//                ]
            ->add(
                'categories',
                EntityType::class,
                [
                    'class' => Category::class,
                ]
            );

        $builder->get('categories')
            ->resetViewTransformers()
            ->addViewTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'csrf_protection' => false,
                'validation_groups' => false,
                'data_class' => Book::class,
            ]
        );
    }
}