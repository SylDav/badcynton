<?php
namespace App\Service;
use App\Repository\PresenceRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PresenceService
{
    /**
     *  @var \PresenceRepository
    */
    private $presenceRepository;

    private $user;


    public function __construct(PresenceRepository $presenceRepository, TokenStorageInterface $tokenStorage)
    {
        $this->presenceRepository = $presenceRepository;
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function getPresence($lessons)
    {
        $lessons_array = array();
        $i = 0;
        foreach ($lessons as $lesson) {
            $lessons_array[$i]['lesson'] = $lesson;
            $presence = $this->presenceRepository->findOneByUserAndLesson($this->user, $lesson);
            $lessons_array[$i]['status'] = ($presence) ? $presence->getStatus() : 2;
            if ($lesson->getDate() > new \DateTime) {
                $lessons_array[$i]['presence'] = "A venir";
            }
            elseif ($presence && $presence->getStatus() == 1) {
                $lessons_array[$i]['presence'] = "Oui";
            }
            else {
                $lessons_array[$i]['presence'] = "Non";
            }
            $i++;
        }
        return $lessons_array;
    }
}
