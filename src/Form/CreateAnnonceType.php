<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Car;
use App\Entity\Rule;
use App\Entity\Ride;
use DateTime;
use Doctrine\DBAL\Types\FloatType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateAnnonceType extends AbstractType
{
    public function __construct(private Security $security) {

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $user = $this->security->getUser();
        
        $builder
            ->add('departure', TextType::class, [
                'label' => 'Depart',
                'required' => true,
            ])
            ->add('destination', TextType::class, [
                'label' => 'Destination',
                'required' => true,
            ])
            ->add('seats', NumberType::class, [
                'label' => 'Place(s)',
                'required' => true,
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix',
                'required' => true,
            ])
            ->add('date', DateTimeType::class, [
                'label' => 'Date',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('car', EntityType::class, [
                'class' => Car::class,

                'by_reference' => false,
                'query_builder' => function (EntityRepository $er) use ($user) {
                    return $er->createQueryBuilder('car')
                        ->where('car.owner = :user')
                        ->setParameter('user', $user);
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ride::class,
        ]);
    }
}
