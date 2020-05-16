<?php
namespace App\Service;
use App\Repository\LessonRepository;
use App\Repository\PresenceRepository;
use App\Service\PresenceService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LessonService
{

    /**
     *  @var \LessonRepository
    */
    private $lessonRepository;

    /**
     *  @var \PresenceRepository
    */
    private $presenceRepository;

    private $user;


    public function __construct(LessonRepository $lessonRepository, PresenceRepository $presenceRepository, presenceService $presenceService, TokenStorageInterface $tokenStorage)
    {
        $this->lessonRepository = $lessonRepository;
        $this->presenceRepository = $presenceRepository;
        $this->presenceService = $presenceService;
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function getAlerts()
    {
        $lessons = $this->lessonRepository->findAllNextByUser($this->user->getClub());
        $alerts[0]['type'] = 'success';
        $alerts[1]['type'] = 'warning';
        $alerts[2]['type'] = 'danger';
        $success = 0;
        $warning = 0;
        $danger = 0;
        foreach ($lessons as $lesson) {
            $presence = $this->presenceRepository->findOneByUserAndLesson($this->user, $lesson);
            if ($presence) {
                if ($presence->getStatus() == 1) {
                    $success++;
                }
            }
            // Si l'utilisateur n'a pas répondu au cours
            else {
                $warning++;
            }
        }
        if ($success > 0) {
            $alerts[0]['message'] = "Il y a " . $success . " cours prochainement. Tiens toi prêt(e) !";
        }
        if ($warning > 0) {
            $alerts[1]['message'] = "Il y a " . $warning . " nouveau(x) cours. Réponds-y ;)";
        }
        return $alerts;
    }

    public function getLessonsForOneUser()
    {
        // On récupère tous les cours pour cet utilisateur
        $lessons = $this->lessonRepository->findAllByUser($this->user->getClub());

        // On retourne ces cours avec la notion de présence au format tableau
        return $this->presenceService->getPresence($lessons);
    }
}
