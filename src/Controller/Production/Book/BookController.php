<?php
namespace App\Controller\Production\Book;

use App\Entity\Production\Book\Book;
use App\Form\Production\Book\BookType;
use App\Repository\Production\Book\BookRepository;
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
 * @Route("/backauth/book") 
 */
class BookController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Lists all page entities.
     *
     * @Route("/", name="admin_book_index")
     * @Method("GET")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function indexAction(
        Request $request,
        PaginatorInterface $paginator,
        BookRepository $entityRepository
    ): Response {

        $query = $entityRepository->createQueryBuilder('p') 
            ->orderBy("p.id", "desc")
            ->getQuery();


        $entities =  $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            50
        );

        return $this->render('production/book/index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new page entity.
     *
     * @Route("/new", name="admin_book_new")
     * @Method({"GET", "POST"})
     * @Security("is_granted('ROLE_DEVELOPER')")
     */
    public function newAction(Request $request): Response
    {

        $entity = new Book();
        $form = $this->createForm(BookType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($entity);
            $this->em->flush($entity);

            $this->addFlash(
                'success',
                'products.savesuccess'
            );
            return $this->redirectToRoute('admin_book_index');
        }

        return $this->render('production/book/form.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing SEOTags entity.
     *
     * @Route("/{id}/edit", name="admin_book_edit")
     * @Method({"GET", "POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function editAction(Request $request, Book $entity): Response
    {

        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_DEVELOPER')) {
            $developer = true;
        } else {
            $developer = false;
        }

        $editForm = $this->createForm(BookType::class, $entity, ['developer' => $developer]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $this->em->flush();
            $this->addFlash("success", "The entry has been saved");

            return $this->redirectToRoute('admin_book_edit', array('id' => $entity->getId()));
        }

        return $this->render('production/book/form.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
        ));
    }

    /**
     *
     * @Route("/{id}/delete", name="admin_book_delete")
     * @Method({"GET"})
     * @Security("is_granted('ROLE_DEVELOPER')")
     * 
     */
    public function deleteAction(Book $entity): Response
    {

        $this->em->remove($entity);
        $this->em->flush();

        $this->addFlash("success", "The entry has been deleted!");

        return $this->redirectToRoute('admin_book_index');
    }
}
