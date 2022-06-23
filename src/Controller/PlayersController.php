<?php

namespace App\Controller;


use App\Entity\Players;
use App\Form\PlayersType;
use App\Form\PlayersTypeEdit;
use App\Repository\PlayersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/players')]
class PlayersController extends AbstractController
{
    #[Route('/', name: 'app_players_index' ,methods: ['GET'])]
    public function index(PlayersRepository $playersRepository): Response
    {
        return $this->render('players/index.html.twig', [
            'players' => $playersRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_new_players', methods: ['GET','POST'])]
    public function new(Request $request, PlayersRepository $playersRepository, SluggerInterface $slugger):Response
    {
        $players = new Players();

        $form = $this->createForm(PlayersType::class,$players);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
				$file = $form->get('photo')->getData();
				$filename = md5(uniqid()) . '.' . $file->guessExtension();
				$file->move($this->getParameter('upload_directory'), $filename);
				$players
					->setPhoto($filename);

            $playersRepository->add($players);
            return $this->redirectToRoute('app_players_index', [],Response::HTTP_SEE_OTHER);

        }

        return $this->renderForm('players/new.html.twig', [
            'players'=>$players,
            'form'=>$form,
        ]);

    }

    #[Route('/{id}', name: 'app_players_show' , methods: ['GET'])]
    public function show(Players $players, PlayersRepository $playersRepository): Response
    {
		$playerss = $playersRepository->findBy(['isTitular' => 1]);
        return $this->render('players/show.html.twig', [
            'players'=>$players,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_players_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Players $players, PlayersRepository $playersRepository): Response
    {


        $form = $this->createForm(PlayersTypeEdit::class, $players);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
		        if ($form->get('photo')->getData()) {
			        $file = $form->get('photo')->getData();
			        $filename = md5(uniqid()) . '.' . $file->guessExtension();
			        $file->move($this->getParameter('upload_directory'), $filename);
			        $players
				        ->setPhoto($filename);
		        }
				if ($form->get('isTitular')->getData() == false) {
					$players
						->setIsTitular(false);
				}elseif($form->get('isTitular')->getData() == true) {
					$players
						->setIsTitular(true);
				}
            $playersRepository->add($players);
            return $this->redirectToRoute('app_players_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('players/edit.html.twig', [
            'players' => $players,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_players_delete', methods: ['POST'])]
    public function delete(Request $request, Players $players, PlayersRepository $playersRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$players->getId(), $request->request->get('_token'))) {
            $playersRepository->remove($players);
        }

        return $this->redirectToRoute('app_players_index', [], Response::HTTP_SEE_OTHER);
    }

	#[Route('/updatePlayerPosition/{id}', name:"updatePlayerPosition", methods:["POST"])]
	public function updatePosition(Request $request,
	                                PlayersRepository $playersRepository)
	{
		$data = json_decode($request->getContent());
		$playerId = $data->playerId;
		$x = $data->x;
		$y = $data->y;
		$player = $playersRepository->findOneBy(['id' => $playerId]);
		$playerX = $player->getPositionX();
		$playerY = $player->getPositionY();
		if($x && $y){
			$doublure = $playersRepository->findOneBy(['position_x' => $x, 'position_y' => $y]);
			if ($doublure){
				$doublureX = $doublure->getPositionX();
				$doublureY = $doublure->getPositionY();
				if($doublureX && $doublureY && !$playerX && !$playerY){
					$doublure->setIsTitular(0);
				}
				$doublure->setPositionX($playerX);
				$doublure->setPositionY($playerY);
				$playersRepository->add($doublure);
			}

			$player->setPositionX($x);
			$player->setPositionY($y);
			$player->setIsTitular(1);
		}else{
			$player->setPositionX(null);
			$player->setPositionY(null);
			$player->setIsTitular(0);
		}
		$playersRepository->add($player);
		$titular = $playersRepository->findBy(['isTitular'=>1]);
		$team = count($titular);
		if ($team < 11){
			return  new Response('Il manque un joueur sur le terrain');
		}elseif ($team > 11){
			return new Response('Vous avez trop de joueurs sur le terrain');
		}else {
			return new Response('Changement effectué', 200);
		}
	}
}
