<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Admin;

use App\Entity\Employee;
use App\Entity\Machine;
use App\Entity\Material;
use App\Entity\Post;
use App\Entity\Site;
use App\Entity\Ticket;
use App\Form\EmployeeType;
use App\Form\MachineType;
use App\Form\MaterialType;
use App\Form\PostType;
use App\Form\SiteType;
use App\Form\TicketType;
use App\Repository\MachineRepository;
use App\Repository\PostRepository;
use App\Security\PostVoter;
use App\Utils\Slugger;
use Doctrine\DBAL\Types\DateType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller used to manage blog contents in the backend.
 *
 * Please note that the application backend is developed manually for learning
 * purposes. However, in your real Symfony application you should use any of the
 * existing bundles that let you generate ready-to-use backends without effort.
 *
 * See http://knpbundles.com/keyword/admin
 *
 * @Route("/admin")
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class BlogController extends AbstractController
{
    /**
     * Lists all Post entities.
     *
     * This controller responds to two different routes with the same URL:
     *   * 'admin_post_index' is the route with a name that follows the same
     *     structure as the rest of the controllers of this class.
     *   * 'admin_index' is a nice shortcut to the backend homepage. This allows
     *     to create simpler links in the templates. Moreover, in the future we
     *     could move this annotation to any other controller while maintaining
     *     the route name and therefore, without breaking any existing link.
     *
     * @Route("/", methods={"GET"}, name="admin_index")
     * @Route("/", methods={"GET"}, name="admin_post_index")
     */
    public function index(PostRepository $posts): Response
    {
        $authorPosts = $posts->findBy(['author' => $this->getUser()], ['publishedAt' => 'DESC']);

        return $this->render('admin/blog/index.html.twig', ['posts' => $authorPosts]);
    }

    /**
     * Add a machine
     *
     * @Route("/machines", methods={"GET"}, name="admin_add_machine")
     *
     */
    public function editMachinesAction(Request $request): Response
    {
        $form = $this->createForm(MachineType::class);

        $machine_repo = $this->getDoctrine()->getRepository(Machine::class);

        $all_machines = $machine_repo->findAll();

        return $this->render('admin/blog/add_machine.html.twig', [
            'machines' => $all_machines,
            'machine_form' => $form->createView()
        ]);
    }

    /**
     * Add a material
     *
     * @Route("/materials", methods={"GET"}, name="admin_add_material")
     *
     */

    public function editMaterialAction(Request $request): Response
    {
        $form = $this->createForm(MaterialType::class);

        $material_repo = $this->getDoctrine()->getRepository(Material::class);

        $all_materials = $material_repo->findAll();

        return $this->render('admin/blog/add_material.html.twig', [
            'materials' => $all_materials,
            'material_form' => $form->createView()
        ]);
    }

    /**
     * Add a employee
     *
     * @Route("/employees", methods={"GET"}, name="admin_add_employee")
     *
     */
    public function editEmployeeAction(Request $request): Response
    {
        $form = $this->createForm(EmployeeType::class);
        $employee_repo = $this->getDoctrine()->getRepository(Employee::class);

        $all_employees= $employee_repo->findAll();

        return $this->render('admin/blog/add_employee.html.twig', [
            'employee_form' => $form->createView(),
            'employees' => $all_employees
        ]);
    }

    /**
     * Add a ticket
     *
     * @Route("/tickets", methods={"GET"}, name="admin_add_ticket")
     *
     */

    public function editTicketAction(Request $request): Response
    {
        $form = $this->createForm(TicketType::class);

        $ticket_repo = $this->getDoctrine()->getRepository(Ticket::class);

        $all_tickets= $ticket_repo->findAll();

        return $this->render('admin/blog/add_ticket.html.twig', [
            'ticket_form' => $form->createView(),
            'tickets' => $all_tickets
        ]);

    }

    /**
     * Add a site
     *
     * @Route("/sites", methods={"GET"}, name="admin_add_site")
     *
     */
    public function editSiteAction(Request $request): Response
    {
        $form = $this->createForm(SiteType::class);

        $site_repo = $this->getDoctrine()->getRepository(Site::class);

        $all_sites = $site_repo->findAll();
        return $this->render('admin/blog/add_site.html.twig', [
            'site_form' => $form->createView(),
            'sites'=> $all_sites
        ]);
    }



    /**
     * Creates a new Post entity.
     *
     * @Route("/new", methods={"GET", "POST"}, name="admin_post_new")
     *
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function new(Request $request): Response
    {
        $post = new Post();
        $post->setAuthor($this->getUser());

        // See https://symfony.com/doc/current/book/forms.html#submitting-forms-with-multiple-buttons
        $form = $this->createForm(PostType::class, $post)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See https://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setSlug(Slugger::slugify($post->getTitle()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See https://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'post.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('admin_post_new');
            }

            return $this->redirectToRoute('admin_post_index');
        }

        return $this->render('admin/blog/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a Post entity.
     *
     * @Route("/{id<\d+>}", methods={"GET"}, name="admin_post_show")
     */
    public function show(Post $post): Response
    {
        // This security check can also be performed
        // using an annotation: @IsGranted("show", subject="post", message="Posts can only be shown to their authors.")
        $this->denyAccessUnlessGranted(PostVoter::SHOW, $post, 'Posts can only be shown to their authors.');

        return $this->render('admin/blog/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * Displays a form to edit an existing Post entity.
     *
     * @Route("/{id<\d+>}/edit",methods={"GET", "POST"}, name="admin_post_edit")
     * @IsGranted("edit", subject="post", message="Posts can only be edited by their authors.")
     */
    public function edit(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setSlug(Slugger::slugify($post->getTitle()));
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'post.updated_successfully');

            return $this->redirectToRoute('admin_post_edit', ['id' => $post->getId()]);
        }

        return $this->render('admin/blog/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Post entity.
     *
     * @Route("/{id}/delete", methods={"POST"}, name="admin_post_delete")
     * @IsGranted("delete", subject="post")
     */
    public function delete(Request $request, Post $post): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_post_index');
        }

        // Delete the tags associated with this blog post. This is done automatically
        // by Doctrine, except for SQLite (the database used in this application)
        // because foreign key support is not enabled by default in SQLite
        $post->getTags()->clear();

        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        $this->addFlash('success', 'post.deleted_successfully');

        return $this->redirectToRoute('admin_post_index');
    }
}
