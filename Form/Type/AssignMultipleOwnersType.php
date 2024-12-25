<?php

namespace MauticPlugin\MyMultiOwnerBundle\Form\Type;

use Mautic\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AssignMultipleOwnersType extends AbstractType
{
    private $userModel;

    public function __construct($userModel)
    {
        $this->userModel = $userModel;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Fetch all active Mautic users
        $users = $this->userModel->getEntities([
            'filter' => ['isPublished' => true], // or any other filter
        ]);

        // Build an array of user choices
        $choices = [];
        foreach ($users as $user) {
            /** @var User $user */
            $choices[$user->getName()] = $user->getId();
        }

        $builder->add(
            'owner_ids',
            ChoiceType::class,
            [
                'choices'  => $choices,
                'multiple' => true,
                'expanded' => false,
                'label'    => 'Select Owners',
                'required' => false,
            ]
        );
    }

    public function getName()
    {
        return 'my_multi_owner_assign_multiple_owners';
    }
}
