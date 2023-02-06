<?php
namespace App\Controller\Production\Category;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\Core\Languages\LanguagesService;
use App\Entity\Core\Languages\Languages;
use App\Entity\Production\Book\Book;
use App\Entity\Production\Category\Category;
use App\Form\Production\Book\BookType;
use App\Form\Production\Category\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Repository\Production\Book\BookRepository;
use App\Repository\Production\Category\CategoryRepository;

/**
 * Page controller.
 *
 * @Route("/backauth/Category")
 */

class CategoryController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    /**
     * Lists all page entities.
     *
     * @Route("/", name="admin_category_index")
     * @Method("GET")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function index(
        Request $request,
        PaginatorInterface $paginator,
        CategoryRepository $entityRepository,
        LanguagesService $languagesService,
        ValidatorInterface $validator
    ): Response
    {
        $mainlocale =  $languagesService->getMain();

        $query = $entityRepository->createQueryBuilder('p')
            ->leftJoin("p.translations", "t")
            ->where("p.status in (1,2) and t.locale='{$mainlocale}' ")
            ->orderBy("p.id", "desc")
            ->getQuery();


        $entities = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            50
        );

        return $this->render('production/category/index.html.twig', array(
            'entities' => $entities,
        ));
    }
     /**
     * Creates a new page entity.
     *
     * @Route("/new", name="admin_category_new")
     * @Method({"GET", "POST"})
     * @Security("is_granted('ROLE_DEVELOPER')")
     */
    public function newAction(Request $request, $id = null): Response
    {
        $entity = new Category();
        $form = $this->createForm(CategoryType::class, $entity, ["developer" => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($entity);
            $this->em->flush($entity);

            $this->addFlash(
                'success',
                'products.savesuccess'
            );

            return $this->redirectToRoute('admin_category_index');
       
        }

        return $this->render('production/category/form.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }
    /**
     * Displays a form to edit an existing page entity.
     *
     * @Route("/{id}/edit", name="admin_category_edit")
     * @Method({"GET", "POST"})
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function editAction(Request $request, Category $entity): Response
    {
        if (!in_array($entity->getStatus(), array(1, 2))) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_DEVELOPER')) {
            $developer = true;
        } else {
            $developer = false;
        }
 
        $editForm = $this->createForm(CategoryType::class, $entity, ["developer" => $developer]);
        $editForm->handleRequest($request);


        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $this->em->flush();

            $this->addFlash(
                'success',
                'products.savesuccess'
            );

            
        }


        return $this->render('production/category/form.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
        ));
    }

    /**
     *
     * @Route("/{id}/delete", name="admin_category_delete")
     * @Method({"GET"})
     * @Security("is_granted('ROLE_DEVELOPER')")
     * 
     */
    public function deleteAction(Category $entity): Response
    {

        if (!in_array($entity->getStatus(), array(1, 2))) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $entity->setStatus(3);

        $this->em->flush();

        $this->addFlash("success", "The entry has been deleted!");

        return $this->redirectToRoute('admin_category_index');
    }


}