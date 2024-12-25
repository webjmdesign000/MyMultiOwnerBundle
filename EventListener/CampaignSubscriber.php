<?php

namespace MauticPlugin\MyMultiOwnerBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Mautic\UserBundle\Model\UserModel;
use Mautic\CampaignBundle\CampaignEvents;
use Mautic\CampaignBundle\Event\CampaignExecutionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CampaignSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserModel
     */
    private $userModel;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(UserModel $userModel, EntityManagerInterface $entityManager)
    {
        $this->userModel     = $userModel;
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            'my_multi_owner.campaign.action.assign_multiple_owners' => ['onAssignMultipleOwners', 0],
        ];
    }

    /**
     * The method that executes when the campaign action is triggered
     */
    public function onAssignMultipleOwners(CampaignExecutionEvent $event)
    {
        // Get the contact
        $lead = $event->getLead();
        if (!$lead) {
            return;
        }

        // Get the configuration from the campaign action form
        $config = $event->getConfig(); // This includes the 'owner_ids' from the form
        if (!isset($config['owner_ids']) || empty($config['owner_ids'])) {
            return;
        }

        // Convert the selected IDs to actual user entities
        $ownerIds = (array) $config['owner_ids'];

        // EXAMPLE: store them in a custom field, or do something else
        // This is where you must handle "multiple owners" logic
        // Because Mautic natively only has 1 'owner', you might do:
        //
        //    $lead->addCustomField('multi_owners', implode(',', $ownerIds));
        //
        // or you create a ManyToMany relation. For demonstration:
        
        $ownersString = implode(',', $ownerIds);
        $lead->addUpdatedField('multi_owners', $ownersString);

        // Persist the changes
        $this->entityManager->persist($lead);
        $this->entityManager->flush();

        // Let Mautic know we changed the lead
        $event->setResult(true);
    }
}
