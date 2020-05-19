<?php
namespace App\Service;
use App\Repository\PresenceRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Presence;

class CalendarService
{

    /**
     *  @var \PresenceRepository
    */
    private $presenceRepository;

    private $user;

    private $em;


    public function __construct(PresenceRepository $presenceRepository, TokenStorageInterface $tokenStorage, EntityManagerInterface $em)
    {
        $this->presenceRepository = $presenceRepository;
        $this->user = $tokenStorage->getToken()->getUser();
        $this->em = $em;
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
            for ($j = 1; $j <= cal_days_in_month(CAL_GREGORIAN,  date_format($from, 'm'),  date_format($from, 'Y')); $j++) {
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
                            $dates[$i][$j]['theme'] = $lesson->getTheme()->getName();
                            $presence = $this->presenceRepository->findOneByUserAndLesson($this->user, $lesson);
                            if ($presence) {
                                if ($presence->getStatus() == 0) {
                                    $dates[$i][$j]['alert'] = "danger";
                                }
                                else {
                                    $dates[$i][$j]['alert'] = "success";
                                }
                            }
                            else {
                                $dates[$i][$j]['alert'] = "warning";
                                // Si le cours est déjà passé, on crée une Presence et on la met à status = 0 (absent)
                                if ($lesson->getDate() < new \DateTime) {
                                    $presence = new Presence();
                                    $presence->setLesson($lesson);
                                    $presence->setStatus(0);
                                    $presence->setUser($this->user);

                                    $this->em->persist($presence);
                                    $this->em->flush();

                                    $dates[$i][$j]['alert'] = "danger";

                                }
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
            "Monday"    => "LUN",
            "Tuesday"   => "MAR",
            "Wednesday" => "MER",
            "Thursday"  => "JEU",
            "Friday"    => "VEN",
            "Saturday"  => "SAM",
            "Sunday"    => "DIM",
        ];

    }

    // public function getTransDay()
    // {
    //     return  [
    //         "Monday"    => "Lundi",
    //         "Tuesday"   => "Mardi",
    //         "Wednesday" => "Mercredi",
    //         "Thursday"  => "Jeudi",
    //         "Friday"    => "Vendredi",
    //         "Saturday"  => "Samedi",
    //         "Sunday"    => "Dimanche",
    //     ];
    //
    // }

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
