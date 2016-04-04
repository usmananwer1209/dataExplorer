<?php

/*
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

// leaving this
require_once 'abstract_model.php';
*/

//require_once 'abstract_mongo_model.php';

class dimensions_model extends CI_model {

    public function __construct(){
        parent::__construct();

        $this->load->model('api_model', 'api');
    }

    /* mongo query
db.TermResults.aggregate(
// Initial document match (uses index, if a suitable one is available)
{ $match: {
entityId: { $in : ['000055', '002178']},
termId : '314019',
FY: 2013,
FQ : 'FY'

}},

// Expand the dimensionalFacts array into a stream of documents
{ $unwind: '$dimensionalFacts' },

// Sort in descending order
{ $sort: {
'companyName' : 1,
'dimensionalFacts.axes': 1,
'FY' : 1,
'FQ' : 1,
'dimensionalFacts.value': 1
}}
)
 */

    public function get_drilldown($entityId, $termId, $year_start, $year_end, $fiscal_type) {

        $results = $this->api->get_termResults_drilldown($entityId, $termId, $year_start . $fiscal_type);
        return $this->process_results($results);
    }

    public function process_results($results) {

        $parents = array();

        $wrapper = new stdClass();
        $wrapper->numDimensions = 0;
        $wrapper->parent = new stdClass();

        $first = TRUE;
        foreach ($results as $resultsIndex => $row) {

            if ($first == TRUE) {

                // this shouldn't matter too much - we're always getting the same termName back
                //$currentTermName = $row->termName;

                // root
                $wrapper->parent->companyName = 'root';

                //$parent->elementName = 'root';
                $wrapper->parent->name = $row['termName'] . ', ' . $row['FY'];

                if ($row['FQ'] != 'FY') {
                    $wrapper->parent->name .= $row['FQ'];
                }

                $wrapper->parent->value = 0.0;
                //$parent->FY = $row['FY'];

                $wrapper->parent->children = array();

                $parents[] = $wrapper->parent;

                $first = FALSE;
            }

            // there should only be 1 of these
            //foreach ($row['dimensionalFacts'] as $dimFactIndex => $dimFact) {

                // change due to change to api
                $dimFact = $row;//['dimensionalFacts'];
                $tempParent = $this->find_parent($parents, $row['companyName'],
                        $dimFact['axes'],
                        $dimFact['axesLabel'] );

                $nameToUse = $dimFact['memberlabel'];
                //$nameToUse = '';
                //foreach ($dimFact['dimensions'] as $dimIndex => $dim) {
//                    $nameToUse .= $dim['memberLabel'] . ', ';
                //}

                // add the child
                // and now process the dimensions
                $childObj = new stdClass();

                // remove the trailing comma
                $childObj->name = rtrim($nameToUse, ', ');
                $childObj->value = $dimFact['value'];
                $childObj->termType = $dimFact['termType'];

                if (!$tempParent->children) {
                    $tempParent->children = array();
                }

                // add to the bottom
                $tempParent->children[] = $childObj;


                // add the values to the parents;
                foreach ($parents as $ap) {
                    $ap->value += abs($dimFact['value']);
                }

                $wrapper->numDimensions += count($tempParent->children);
            //}
        }

        return $wrapper;
    }

    function find_parent(&$allParents, $companyName, /*$elementName,*/ $axesElementName, /*, $year,*/ $axesLabel /*, $termVal*/)
    {
        $curParent = array_pop($allParents);

        // first check for company name
        if ($curParent->companyName != $companyName) {

            // unwind all the parents
            if ($curParent->companyName != 'root') {
                do {
                    $someParent = array_pop($allParents);
                } while ($someParent->companyName != 'root');
            }
            else {
                $someParent = $curParent;
            }
            // put root back
            $grandpa = $allParents[] = $someParent;

            //$nameToUse = $companyName;// . ', ' . $year;

            // skip the axes on purpose - need to creat a new company first
            $this->create_parent($allParents, $grandpa, $companyName, $companyName);

            // and try this again
            return $this->find_parent($allParents, $companyName, $axesElementName, $axesLabel);
        }
/*
        else if ($curParent->year != $year) {

            // next parent up is the company, which is the one we want
            $grandpa = array_pop($allParents);

            $nameToUse = $year;
        }
*/
        else if (!$curParent->axesElementName || ($curParent->axesElementName != $axesElementName)) {

            // if we get here, the company is the same but we have a new axes
            if ($curParent->axesElementName) {
                // we have a dimension, but it's the wrong one

                // so pop off the stack until we get back to the company
                do {
                    $curParent = array_pop($allParents);
                } while ($curParent->axesElementName);

                // and put company back onto the stack
                $allParents[] = $curParent;
            }
            else {
                // no dimension, we're sitting on the company, put it back
                $allParents[] = $curParent;
            }


            return $this->create_parent($allParents, $curParent, str_replace('|', ', ', $axesLabel), $companyName, $axesElementName);
        }
        else {
            // everything matches, put the curParent back on and return
            $allParents[] = $curParent;

            return $curParent;
        }

        // should never get here

        //$allParents[0]->value += $termVal;
        // push grandpa and child onto the parent stack
        //$allParents[] = $grandpa;

        //return $curParent;
    }

    function create_parent(&$allParents, &$grandpa, $nameToUse, $companyName, $axesElementName=null ) {

        $newParent = new stdClass();

        //$curParent->elementName = $elementName;
        $newParent->axesElementName = $axesElementName;

        $newParent->companyName = $companyName;
        $newParent->name = $nameToUse;
        $newParent->value = 0; //$termVal;
        //$curParent->year = $year;
        $newParent->children = array();

        $grandpa->children[] = $newParent;

        $allParents[] = $newParent;

        return $newParent;
    }
}