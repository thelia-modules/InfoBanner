<?php
namespace InfoBanner\Loop;

use InfoBanner\Model\Infobanner;
use InfoBanner\Model\InfobannerQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;

class IbLoop extends BaseLoop implements PropelSearchLoopInterface
{
    protected function getArgDefinitions(): ArgumentCollection
    {
        return new ArgumentCollection(
            Argument::createIntListTypeArgument('id'),
            Argument::createIntListTypeArgument('title')
        );
    }

    public function buildModelCriteria(): InfobannerQuery|ModelCriteria
    {
        $query = InfoBannerQuery::create();

        $id = $this->getId();
        if (null !== $id) {
            $query->filterById($id, Criteria::IN);
        }

        $title = $this->getTitle();
        if (null !== $title) {
            $query->filterByTitle($title, Criteria::IN);
        }

        return $query;
    }

    public function parseResults(LoopResult $loopResult): LoopResult
    {
        /** @var Infobanner $infoBanner */
        foreach ($loopResult->getResultDataCollection() as $infoBanner) {
            $loopResultRow = new LoopResultRow($infoBanner);

            $loopResultRow
                ->set('ID', $infoBanner->getId())
                ->set('TITLE', $infoBanner->getTitle())
            ;

            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }
}