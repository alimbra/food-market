<?php

namespace App\Infrastructure\Form;

use App\Domain\Entity\Supplier;
use App\Enum\EnumIAGatewayType;
use App\Form\Service\GoogleFormType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ImportFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('supplier', EntityType::class, [
                'label' => 'Fournisseur',
                'class' => Supplier::class,
                'query_builder' => function (EntityRepository $er): QueryBuilder {

                return $er->createQueryBuilder('u')
                    ->orderBy('u.name', 'ASC');
                },
                'choice_label' => function (Supplier $supplier): string {
                    return $supplier->getName();
                },
                'attr' =>
                [
                    'id' => 'formFile',
                    'class' => 'form-select',
                ]
            ])
            ->add('file', FileType::class, [
                'label' => 'Mercurial (Csv file)',
                'mapped' => false,

                'required' => true,

                'constraints' => [
                    new File([

                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'text/csv',
                        ],
                        'mimeTypesMessage' => 'invalid csv file',
                    ])
            ]])
            ->add('import', SubmitType::class, [
                'label' => 'Importer Fichier',
                'attr' => [
                    'class' => 'btn btn-primary',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
