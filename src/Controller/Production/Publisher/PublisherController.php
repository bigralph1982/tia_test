<?php
namespace App\Controller\Production\Publisher;

use App\Entity\Production\Publisher\Publisher;
use App\Form\Production\Publisher\PublisherType;
use App\Repository\Production\Publisher\PublisherRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;




/**
 *
 * @Route("/backauth/publisher") 
 */
class PublisherController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Lists all page entities.
     *
     * @Route("/", name="admin_publisher_index")
     * @Method("GET")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function indexAction(
        Request $request,
        PaginatorInterface $paginator,
        PublisherRepository $entityRepository
    ): Response {

        $query = $entityRepository->createQueryBuilder('p') 
            ->orderBy("p.id", "desc")
            ->getQuery();


        $entities =  $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            50
        );

        return $this->render('production/publisher/index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new page entity.
     *
     * @Route("/new", name="admin_publisher_new")
     * @Method({"GET", "POST"})
     * @Security("is_granted('ROLE_DEVELOPER')")
     */
    public function newAction(Request $request): Response
    {

        $entity = new Publisher();
        $form = $this->createForm(PublisherType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($entity);
            $this->em->flush($entity);

            $this->addFlash(
                'success',
                'products.savesuccess'
            );
            return $this->redirectToRoute('admin_publisher_index');
        }

        return $this->render('production/publisher/form.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing SEOTags entity.
     *
     * @Route("/{id}/edit", name="admin_publisher_edit")
     * @Method({"GET", "POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function editAction(Request $request, Publisher $entity): Response
    {

        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_DEVELOPER')) {
            $developer = true;
        } else {
            $developer = false;
        }

        $editForm = $this->createForm(PublisherType::class, $entity, ['developer' => $developer]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $this->em->flush();
            $this->addFlash("success", "The entry has been saved");

            return $this->redirectToRoute('admin_publisher_edit', array('id' => $entity->getId()));
        }

        return $this->render('production/publisher/form.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
        ));
    }

    /**
     *
     * @Route("/{id}/delete", name="admin_publisher_delete")
     * @Method({"GET"})
     * @Security("is_granted('ROLE_DEVELOPER')")
     * 
     */
    public function deleteAction(Publisher $entity): Response
    {

        $this->em->remove($entity);
        $this->em->flush();

        $this->addFlash("success", "The entry has been deleted!");

        return $this->redirectToRoute('admin_publisher_index');
    }
}
