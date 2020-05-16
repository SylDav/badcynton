<?php
namespace App\Service;
use App\Repository\PresenceRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CalendarService
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

    public function createCalendar(array $lessons, $role)
    {
        $from = new \DateTime;
        $from = $from->modify('-2 months');
        $to = new \DateTime;
        $to = $to->modify('+2 months');
        $dates = [];
        $i = 1;
        while ($from <= $to) {
            for ($j=1; $j <= cal_days_in_month(CAL_GREGORIAN,  date_format($from, 'm'),  date_format($from, 'Y')); $j++) {
                $dates[$i][$j]['date'] = new \DateTime(date_format($from, 'Y') . '-' . date_format($from, 'm') . '-' . $j);
                foreach ($lessons as $lesson) {
                    if ($lesson->getDate() == $dates[$i][$j]['date']) {
                        $dates[$i][$j]['id'] = $lesson->getId();
                        // Si on veut afficher l'index de l'ADMIN
                        if ($role == 'admin') {
                            $dates[$i][$j]['joueurs'] = 0;
                            $presences = $lesson->getPresences();
                            $dates[$i][$j]['theme'] = $lesson->getTheme()->getName();
                            $dates[$i][$j]['club'] = $lesson->getClub()->getName();
                            foreach ($presences as $presence) {
                                if ($presence->getStatus() == 1) {
                                    $dates[$i][$j]['joueurs']++;
                                }
                            }
                        }
                        // Si on veut afficher l'index d'un USER
                        else {
                            $presence = $this->presenceRepository->findOneByUserAndLesson($this->user, $lesson);
                            if ($presence) {
                                $dates[$i][$j]['status'] = $presence->getStatus();
                            }
                            else {
                                $dates[$i][$j]['status'] = 2;
                            }
                        }
                        break;
                    }
                    else {
                        $dates[$i][$j]['id'] = 0;
                        $dates[$i][$j]['joueurs'] = null;
                    }
                }
            }
            $from = $from->add(new \DateInterval('P1M'));
            $i++;
        }

        return $dates;
    }

    public function getTransDay()
    {
        return  [
            "Monday"    => "Lundi",
            "Tuesday"   => "Mardi",
            "Wednesday" => "Mercredi",
            "Thursday"  => "Jeudi",
            "Friday"    => "Vendredi",
            "Saturday"  => "Samedi",
            "Sunday"    => "Dimanche",
        ];

    }

    public function getTransMonth()
    {
        return [
            "Jan"       => "Janvier",
            "Feb"       => "Février",
            "Mar"       => "Mars",
            "Apr"       => "Avril",
            "May"       => "Mai",
            "Jun"       => "Juin",
            "Jul"       => "Juillet",
            "Aug"       => "Août",
            "Sep"       => "Septembre",
            "Oct"       => "Octobre",
            "Nov"       => "Novembre",
            "Dec"       => "Décembre",
        ];
    }
}
