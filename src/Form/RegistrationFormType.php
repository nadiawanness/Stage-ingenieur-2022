<?php

namespace App\Form;

use App\Entity\CoreUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'username',
                TextType::class,
                [
                  'attr' => [
                    'class' => 'form-control',
                  ],
                  'label' => 'Username',
                ]
            )
            ->add(
                'usernameCanonical',
                TextType::class,
                [
                  'attr' => [
                    'class' => 'form-control',
                  ],
                  'label' => 'Username Canonical',
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                  'attr' => [
                    'class' => 'form-control',
                  ],
                  'label' => 'E-mail',
                ]
            )
            ->add(
                'emailCanonical',
                EmailType::class,
                [
              'attr' => [
                'class' => 'form-control',
              ],
              'label' => 'E-mail Canonical',
            ]
            )

            ->add(
                'civility',
                TextType::class,
                [
              'attr' => [
                'class' => 'form-control',
              ],
              'label' => 'Civility',
            ]
            )

            ->add(
                'type',
                TextType::class,
                [
                  'attr' => [
                    'class' => 'form-control',
                  ],
                  'label' => 'Type',
                ]
            )
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password',
                            'class' => 'form-control',
                        ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])

            ->add('confirmPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password',
                            'class' => 'form-control',
                        ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CoreUser::class,
        ]);
    }
}
