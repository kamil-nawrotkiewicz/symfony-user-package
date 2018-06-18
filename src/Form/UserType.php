<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName', textType::class, [
                'label' => 'form.fullName',
                'translation_domain' => 'user'])
            ->add('username',textType::class, [
                'label' => 'form.username',
                'translation_domain' => 'user'])
            ->add('email',
                textType::class, [
                    'label' => 'form.email',
                    'translation_domain' => 'user'])
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => [
                    'label' => 'form.password',
                    'translation_domain' => 'user'],
                'second_options' => [
                    'label' => 'form.plainPassword',
                    'translation_domain' => 'user'],
                'invalid_message' => 'Passwords must match.',
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
