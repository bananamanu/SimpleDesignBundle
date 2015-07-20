<?php

namespace Bananamanu\SimpleDesignBundle\Helper;

use eZ\Publish\API\Repository\Repository;
use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\API\Repository\Values\Content\LocationQuery;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\Values\Content\Query\SortClause;

/**
 * Helper for searching and displaying sub-element
 */
class SubElementHelper
{
    /**
     * @var \eZ\Publish\API\Repository\Repository
     */
    private $repository;

    public function __construct( Repository $repository )
    {
        $this->repository = $repository;
    }

    /**
     * Returns latest published content that is located under $pathString and matching $contentTypeIdentifier.
     * The whole subtree will be passed through to find content.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Location $rootLocation Root location we want to start content search from.
     * @param string[] $includeContentTypeIdentifiers Array of ContentType identifiers we want content to match.
     * @param \eZ\Publish\API\Repository\Values\Content\Query\Criterion $criterion Additional criterion for filtering.
     * @param int|null $limit Max number of items to retrieve. If not provided, default limit will be used.
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Content[]
     */
    public function getLatestContent( Location $rootLocation, array $includeContentTypeIdentifiers = array(), Criterion $criterion = null, $limit = null )
    {
        $criteria = array(
            new Criterion\Subtree( $rootLocation->pathString ),
            new Criterion\Visibility( Criterion\Visibility::VISIBLE )
        );

        if ( $includeContentTypeIdentifiers )
            $criteria[] = new Criterion\ContentTypeIdentifier( $includeContentTypeIdentifiers );

        if ( !empty( $criterion ) )
            $criteria[] = $criterion;

        $query = new Query(
            array(
                'criterion' => new Criterion\LogicalAnd( $criteria ),
                'sortClauses' => array( new SortClause\DatePublished( Query::SORT_DESC ) )
            )
        );
        $query->limit = $limit ?: $this->defaultMenuLimit;

        return $this->searchHelper->buildListFromSearchResult( $this->repository->getSearchService()->findContent( $query ) );
    }

    /**
     * Returns Location objects parent of the locationId provided
     *
     * Criterion can be passed as parameter to refine the search
     *
     * @param int $locationId
     * @param \eZ\Publish\API\Repository\Values\Content\Query\Criterion $criterion Additional criterion for filtering.
     * @param int $limit
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Location[] Location objects, indexed by their contentId.
     */
    public function getSubElementLocation( $locationId, Criterion $criterion = null, $limit = null )
    {
        $criteria = array(
            new Criterion\ParentLocationId( $locationId ),
            new Criterion\Visibility( Criterion\Visibility::VISIBLE )
        );

        if ( !empty( $criterion ) )
            $criteria[] = $criterion;

        $query = new LocationQuery(
            array(
                'criterion' => new Criterion\LogicalAnd( $criteria ),
                'sortClauses' => array( new SortClause\Location\Priority( LocationQuery::SORT_ASC ) )
            )
        );

        if ( $limit != null )
            $query->limit = $limit;

        $searchResult = $this->repository->getSearchService()->findLocations( $query );

        $list = array();
        foreach ( $searchResult->searchHits as $searchHit )
        {
            $list[$searchHit->valueObject->id] = $searchHit->valueObject;
        }

        return $list;
    }

}
