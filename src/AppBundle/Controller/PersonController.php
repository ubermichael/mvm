<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Person;
use AppBundle\Form\PersonType;

/**
 * Person controller.
 *
 * @IsGranted("ROLE_USER")
 * @Route("/person")
 */
class PersonController extends Controller implements PaginatorAwareInterface {

    use PaginatorTrait;

    /**
     * Lists all Person entities.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="person_index", methods={"GET"})
     * @Template()
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(Person::class, 'e')->orderBy('e.sortableName', 'ASC');
        $query = $qb->getQuery();
        $paginator = $this->get('knp_paginator');
        $people = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'people' => $people,
        );
    }

    /**
     * Typeahead API endpoint for Person entities.
     *
     * @param Request $request
     *
     * @Route("/typeahead", name="person_typeahead", methods={"GET"})
     * @return JsonResponse
     */
    public function typeahead(Request $request) {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse([]);
        }
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Person::class);
        $data = [];
        foreach ($repo->typeaheadQuery($q) as $result) {
            $data[] = [
                'id'   => $result->getId(),
                'text' => (string) $result,
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * Search for Person entities.
     *
     * @param Request $request
     *
     * @Route("/search", name="person_search", methods={"GET"})
     * @Template()
     * @return array
     */
    public function searchAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Person');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);
            $paginator = $this->get('knp_paginator');
            $people = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        }
        else {
            $people = array();
        }

        return array(
            'people' => $people,
            'q'      => $q,
        );
    }

    /**
     * Creates a new Person entity.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new", name="person_new", methods={"GET","POST"})
     * @Template()
     */
    public function newAction(Request $request) {
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();

            $this->addFlash('success', 'The new person was created.');

            return $this->redirectToRoute('person_show', array('id' => $person->getId()));
        }

        return array(
            'person' => $person,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Person entity in a popup.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new_popup", name="person_new_popup", methods={"GET","POST"})
     * @Template()
     */
    public function newPopupAction(Request $request) {
        return $this->newAction($request);
    }

    /**
     * Finds and displays a Person entity.
     *
     * @param Person $person
     *
     * @return array
     *
     * @Route("/{id}", name="person_show", methods={"GET"})
     * @Template()
     */
    public function showAction(Person $person) {

        return array(
            'person' => $person,
        );
    }

    /**
     * Displays a form to edit an existing Person entity.
     *
     *
     * @param Request $request
     * @param Person $person
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="person_edit", methods={"GET","POST"})
     * @Template()
     */
    public function editAction(Request $request, Person $person) {
        $editForm = $this->createForm(PersonType::class, $person);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The person has been updated.');

            return $this->redirectToRoute('person_show', array('id' => $person->getId()));
        }

        return array(
            'person'    => $person,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Person entity.
     *
     *
     * @param Request $request
     * @param Person $person
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/delete", name="person_delete", methods={"GET"})
     */
    public function deleteAction(Request $request, Person $person) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($person);
        $em->flush();
        $this->addFlash('success', 'The person was deleted.');

        return $this->redirectToRoute('person_index');
    }
}
