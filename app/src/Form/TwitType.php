<?php

namespace App\Form;

use App\Twit\Domain\Twit\TwitContent;
use App\Twit\Domain\Twit\TwitIsTooLongException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class TwitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'content',
                TextareaType::class,
                ['label' => 'Send your twit', 'attr' => ['rows' => 10, 'cols' => 60],
                    'constraints' => new Callback(function (string|null $object, ExecutionContextInterface $context) {
                        try {
                            TwitContent::fromString($object);
                        } catch (TwitIsTooLongException $exception) {
                            $context->buildViolation($exception->getMessage())
                                ->atPath('content')
                                ->addViolation();
                        } catch (\TypeError $typeError) {
                            $context->buildViolation($typeError->getMessage())
                                ->atPath('content')
                                ->addViolation();
                        }
                    })
                ]
            )
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
