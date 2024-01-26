<?php

namespace App\Controller;

use App\Entity\District;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DistrictsController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;    
    }

    #[Route('/districts', name: 'app_districts')]
    public function index(Request $request): Response
    {
        $validSortFields = ['id', 'name', 'landMass', 'population', 'city', 'imagePath'];

        // Retrieve sort parameters from the request
        $sortField = $request->query->get('sort', 'id');
        $sortOrder = strtoupper($request->query->get('order', 'ASC'));

        // Validate sort field
        if (!in_array($sortField, $validSortFields)) {
            throw $this->createNotFoundException('Invalid sort field.');
        }

        // Validate sort order
        if (!in_array($sortOrder, ['ASC', 'DESC'])) {
            throw $this->createNotFoundException('Invalid sort order.');
        }

        // Retrieve entities based on sorting and filtering
        $repository = $this->em->getRepository(District::class);

        // Get the search filter from the request
        $filterName = $request->query->get('filterName');

        // Get the population range filters from the request
        $minPopulation = $request->query->get('minPopulation');
        $maxPopulation = $request->query->get('maxPopulation');

        // Use a custom query to handle the search filter and population range filters
        $queryBuilder = $repository->createQueryBuilder('d');

        if ($filterName) {
            $queryBuilder->andWhere('d.name LIKE :name')
                ->setParameter('name', '%' . $filterName . '%');
        }

        // Add population range filter to the query
        if ($minPopulation !== null) {
            $queryBuilder->andWhere('d.population >= :minPopulation')
                ->setParameter('minPopulation', $minPopulation);
        }

        if ($maxPopulation !== null) {
            $queryBuilder->andWhere('d.population <= :maxPopulation')
                ->setParameter('maxPopulation', $maxPopulation);
        }

        // Add sorting to the query
        $queryBuilder->orderBy('d.' . $sortField, $sortOrder);

        // Execute the query
        $districts = $queryBuilder->getQuery()->getResult();

        return $this->render('districts/index.html.twig', [
            'districts' => $districts,
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
        ]);
    }
}
