<?php
namespace CsnSocial\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class GroupRepository extends EntityRepository {

	/*public function getRecentBugs($number = 30) {
        $dql = "SELECT b, e, r FROM \Application\Entity\Bug b JOIN b.engineer e JOIN b.reporter r ORDER BY b.created DESC";

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setMaxResults($number);

        return $query->getResult();
    }*/
    
}