<?php

namespace App\Controller\Core\SEO;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

use App\Entity\Core\SEO\SEOTags;
use App\Form\Core\SEO\SEOTagsType;
use App\Repository\Core\SEO\SEOTagsRepository;


/**
 * SEOTags controller.
 *
 * @Route("/backauth/seo") 
 */
class SEOTagsController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Lists all page entities.
     *
     * @Route("/", name="admin_seo_index")
     * @Method("GET")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function indexAction(
        Request $request,
        PaginatorInterface $paginator,
        SEOTagsRepository $entityRepository
    ): Response {

        $query = $entityRepository->createQueryBuilder('p') 
            ->orderBy("p.id", "desc")
            ->getQuery();


        $entities =  $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            50
        );

        return $this->render('core/seo/index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new page entity.
     *
     * @Route("/new", name="admin_seo_new")
     * @Method({"GET", "POST"})
     * @Security("is_granted('ROLE_DEVELOPER')")
     */
    public function newAction(Request $request): Response
    {

        $entity = new SEOTags();
        $form = $this->createForm(SEOTagsType::class, $entity, ['developer' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($entity);
            $this->em->flush($entity);

            $this->addFlash(
                'success',
                'products.savesuccess'
            );
            return $this->redirectToRoute('admin_seo_index');
        }

        return $this->render('core/seo/form.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing SEOTags entity.
     *
     * @Route("/{id}/edit", name="admin_seo_edit")
     * @Method({"GET", "POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function editAction(Request $request, SEOTags $entity): Response
    {

        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_DEVELOPER')) {
            $developer = true;
        } else {
            $developer = false;
        }

        $editForm = $this->createForm(SEOTagsType::class, $entity, ['developer' => $developer]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $this->em->flush();
            $this->addFlash("success", "The entry has been saved");

            return $this->redirectToRoute('admin_seo_edit', array('id' => $entity->getId()));
        }

        return $this->render('core/seo/form.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
        ));
    }

    /**
     *
     * @Route("/{id}/delete", name="admin_seo_delete")
     * @Method({"GET"})
     * @Security("is_granted('ROLE_DEVELOPER')")
     * 
     */
    public function deleteAction(SEOTags $entity): Response
    {

        $this->em->remove($entity);
        $this->em->flush();

        $this->addFlash("success", "The entry has been deleted!");

        return $this->redirectToRoute('admin_seo_index');
    }
}
