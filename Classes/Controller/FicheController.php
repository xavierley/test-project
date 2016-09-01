<?php
namespace Emagineurs\EAnnuaires\Controller;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 xley <xley@e-magineurs.com>
 *  
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
 
 use \TYPO3\CMS\Extbase\Utility\DebuggerUtility;
 
/**
 *
 *
 * @package e_annuaires
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class FicheController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

    /**
	 * ficheRepository
	 *
	 * @var \Emagineurs\EAnnuaires\Domain\Repository\FicheRepository
	 * @inject
	 */
	protected $ficheRepository;
    
    /**
	 * cityRepository
	 *
	 * @var \Emagineurs\EAnnuaires\Domain\Repository\CityRepository
	 * @inject
	 */
	protected $cityRepository;
    
    /**
	 * communeRepository
	 *
	 * @var \Emagineurs\EAnnuaires\Domain\Repository\CityRepository
	 * @inject
	 */
	protected $communeRepository;
    
    /**
	 * categorieRepository
	 *
	 * @var \Emagineurs\EAnnuaires\Domain\Repository\CategorieRepository
	 * @inject
	 */
	protected $categorieRepository;
    
    /**
     * districtRepository
     *
     * @var \Emagineurs\EAnnuaires\Domain\Repository\DistrictRepository
     * @inject
     */
    protected $districtRepository;

    /**
	 * MandatRepository
	 *
	 * @var \Emagineurs\EAnnuaires\Domain\Repository\MandatRepository
	 * @inject
	 */
	protected $mandatRepository;

    /**
	 * champBddRepository
	 *
	 * @var \Emagineurs\EAnnuaires\Domain\Repository\Conf\ChampbddRepository
	 * @inject
	 */
	protected $champBddRepository;

	/**
	 * SignalSlot Dispatcher
	 *
	 * @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher
	 * @inject
	 */
	protected $signalSlotDispatcher;
	
	/**
	 * initialize create action
	 * allow creation of submodel company
	 */
	public  function initializeAction ()  { 		
		if  ( $this -> arguments -> hasArgument ( 'newFiche' ))  { 
			$this -> arguments -> getArgument ( 'newFiche' )-> getPropertyMappingConfiguration ()-> setTargetTypeForSubProperty ( 'upload' ,  'array' ); 
		}
        
        $this->TSinPlugin();
        $this->currentUser = $GLOBALS['TSFE']->fe_user->user;
		
		// On instancie ici le generalUtility de e_annuaires
		$this->utility = $this->objectManager->get(\Emagineurs\EAnnuaires\Utility\GeneralUtility::class);
        
        $this->useTsIfFlexIsEmpty();
	}
    
    public function useTsIfFlexIsEmpty(){
        $confTsComplete = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
           
        $confTSWithDots = $confTsComplete['plugin.']['tx_eannuaires.']['settings.'];
        
        $confTS = \Emagineurs\EAnnuaires\Utility\GeneralUtility::removeDotsInConf($confTSWithDots);
        
        foreach($confTS as $conf => $value){
            if(!array_key_exists($conf,$this->settings) || empty($this->settings[$conf])){
                if(trim($value) != '' && !empty($value)){  
                    $this->settings[$conf] = $value;
                } 
            }
        }
    }
	
	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {
		$this->ficheRepository->setSettings($this->settings, array('liste' => 1, 'cObj' => $this->configurationManager->getContentObject()));	
        
		$currentRequest = $this->request->getArguments();  
		if($currentRequest['page'] == 0){
		   $currentRequest['page'] = 1;
		}
        
        if(!empty($currentRequest['communeUid'])){
		   $commune = $this->getCommuneUid($currentRequest['communeUid']);
		}
        if(!empty($currentRequest['commissionUid'])){
		   $commission = $this->getCommissionUid($currentRequest['commissionUid']);
		}
        if($this->settings['getListe']){
			foreach($this->settings['getListe'] as $listeNom => $listeItems){
				$this->view->assign($listeNom,$this->getListeItems($listeItems));
			}
		}
        
        $nbPage = $this->getNbPage($currentRequest);
        
        $paginate = $this->getPaginate($nbPage);
                
		$fiches = $this->ficheRepository->findWithSettings($currentRequest);
		

		//On rajoute les champs à tester pour l'abécédaire ici (évite d'apeller x fois la fonction);
		if(isset($this->settings['menu_abc']) && isset($this->settings['confAbecedaire'])){
			if(isset($this->settings['filtre']['ficheType']['champFiltre']) && $this->settings['filtre']['ficheType']['champFiltre'] = 'typeelement'){
				$item = $this->settings['filtre']['ficheType']['value'];
				$fieldToTest = $this->getFieldToTest($this->settings, $item);
			}
		}
		if(isset($this->settings['menu_abc'])){
            $abc = array();
            foreach($fiches as $fiche){
                $abc[$fiche->getFirstletter()][] = $fiche;
            }
		}
        //Adrien : créé le cache avec cette recherche pour cette session (flèche suivant/précédant en détail Elus front) ::class.user_backlink
        //TODOO : modifier pour permettre de mettre en cache plusieurs param de recherche pour un même utilisateur
        //créer un tableau 'search' dans la session utilisateur. chaque entrée du tableau aura un id spécifique à la recherche. Chaque Id aura une entrée en BDD
        $fichesRawResult = $this->ficheRepository->findWithSettings($currentRequest,0,TRUE);
		
		$ficheKkache = array();
		foreach($fichesRawResult as $cacheFiche){
			$ficheKkache[] = $cacheFiche['uid'];
		}

		//obligé d'envoyer un cookie pour qu'il garde mon id de session
		$GLOBALS['TSFE']->fe_user->fetchSessionData();
		$GLOBALS['TSFE']->fe_user->setKey('ses', 'test', array('keepit'=>1));
		$GLOBALS['TSFE']->fe_user->storeSessionData();
		$sesId = $GLOBALS['TSFE']->fe_user->id;
		\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager')->getCache('txeannuaires_search_cache')->set($sesId, $ficheKkache, array(), 3600);
			
        if($this->settings['organigramme']['chef']){
            $organigramme = $this->generateOrganigramme($fiches);
        } 

		$char = '';
		if(isset($this->settings['one_letter'])){
			//On rajoute la lettre de l'abécédaire si jamais elle est définie
			if(isset($_GET['char'])){
				$char = trim(htmlspecialchars($_GET['char']));
			} 
		} 
		$this->view->assign('countFiches',$paginate);
		$this->view->assign('nbPage',$nbPage);
		$this->view->assign('commune',$commune);
		$this->view->assign('commissionItem',$commission);
		$this->view->assign('currentPage',intval($currentRequest['page']));
		$this->view->assign('nextPage',intval($currentRequest['page']+1));
		$this->view->assign('prevPage',intval($currentRequest['page']-1));
		$this->view->assign('settings', $this->settings);
		$this->view->assign('fiches', $fiches);
		$this->view->assign('organigramme', $organigramme);
		if(isset($fieldToTest)){
			$this->view->assign('fieldToTest', $fieldToTest);
		}
		$this->view->assign('char', $char);
		$this->view->assign('abc', $abc);
		
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'BeforeRenderView', array($fiches, $this));
	}
    
    public function getListeItems($listeItems){
		$select = '*';
		$table = $listeItems;
		$where = ' deleted = 0 AND hidden = 0 ';
		
        $req = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows($select,$table,$where);
		
		$result = array();
		foreach($req as $item){
			if($this->utility->checkRootPage($item['pid']) === true){
				$result[] = $item;
 			}
		}
		
		return $result;
	}
    
	public function getCommuneUid($communeUid){
        $commune = $this->communeRepository->findByUid($communeUid);
        
        return $commune;
    }    
	
	public function getCommissionUid($commissionUid){
		$select = '*';
		$table = 'tx_eannuaires_domain_model_commission';
		$where = ' deleted = 0 AND hidden = 0 AND uid = '.$commissionUid;
		
        $req = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows($select,$table,$where);
        
        return $req[0];
    }
    
    public function generateOrganigramme($fiches){
        $result = array();
        
        $chefField = $this->settings['organigramme']['chef'];
        $getterChef = 'get'.ucfirst($chefField);
        foreach($fiches as $fiche){            
            if($fiche->$getterChef() == 1){
                foreach($fiche->getCategories() as $category){
                    $result[$category->getUid()][0][$fiche->getUid()] = $fiche;
                    break;
                }
            } else {
                foreach($fiche->getCategories() as $category){
                    $result[$category->getUid()][1][$fiche->getUid()] = $fiche;
                    break;
                }
            }
            ksort($result);
        }
        
        return $result;
    }
	
	public function getFieldToTest($settings, $item){
        if($settings['confAbecedaire'][$item]){
            $field = $settings['confAbecedaire'][$item];

            if(!empty($GLOBALS['TCA']['tx_eannuaires_domain_model_fiche']['columns'][$field]['config']['foreign_table'])){
                $tableFieldsArray = $GLOBALS['TYPO3_DB']->admin_get_fields($GLOBALS['TCA']['tx_eannuaires_domain_model_fiche']['columns'][$field]['config']['foreign_table']);
          
                if(array_key_exists('title',$tableFieldsArray)){
                    return array(
                        'get'.ucFirst($field),
                        'get'.ucFirst('title')
                    );
                } elseif(array_key_exists('titre',$tableFieldsArray)){
                    return array(
                        'get'.ucFirst($field),
                        'get'.ucFirst('titre')
                    );
                }
            }
        } else {
            $field = 'title';
        }
        return 'get'.ucFirst($field);
	}
    	
    public function getNbPage($currentRequest, $search = 0){
        if($search){            
            $this->ficheRepository->setSettings($this->settings,array('search' => 1));
        }

        $countFiches = $this->ficheRepository->findWithSettings($currentRequest,1);
        

        if($this->settings['nbElement'] < 1 ){
            $this->settings['nbElement'] = 6;
        }

        $nbPage = ceil($countFiches/$this->settings['nbElement']);
                
        return $nbPage;
    }
    
    public function getPaginate($nbPage){
        $paginate = array();
        
        $i = 1;
        while ($i <= $nbPage){
            $paginate[] = $i;
            $i++;
        }
        
        return $paginate;
    }

	/**
	 * action search
	 *
	 * @return void
	 */
	public function searchAction() {
        $this->ficheRepository->setSettings($this->settings, array('cObj' => $this->configurationManager->getContentObject()));	
		$currentRequest = $this->request->getArguments();
		
        $searchObjet = $this->objectManager->get('Emagineurs\EAnnuaires\Domain\Model\Search');
		if(!empty($currentRequest['search'])){
			$searchObjet = $this->setSearchObjet($searchObjet,$currentRequest);
		}
		              
        $properties = $this->settings['search']['properties'];
        
        foreach($properties as $key => $property){
            if(is_array($property)){
                if(array_key_exists('array',$property)){
                    $currentArray = $this->utility->getArrayForSearchForm($property['array'],$this->settings);
                    
                    $this->view->assign($key, $currentArray);
                }
            }
        }        
        
		$this->view->assign('settings', $this->settings);
		$this->view->assign('searchObjet', $searchObjet);
		
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'BeforeRenderView', array($searchObjet, $this));
	}
	
	public function setSearchObjet($searchObjet,$currentRequest){
		if(is_array($currentRequest['search']) && count($currentRequest['search']) > 0){   
			foreach($currentRequest['search'] as $field => $value){
				$setMethod = 'set'.ucFirst($field);
				
				$searchObjet->$setMethod($value);
			}
		}
		
		return $searchObjet;
	}
	    
	/**
	 * action resultat
	 *
	 * @return void
	 */
	public function resultatAction() {
        $this->ficheRepository->setSettings($this->settings, array('cObj' => $this->configurationManager->getContentObject()));
		$currentRequest = $this->request->getArguments();
		
		if($currentRequest['page'] == 0){
		   $currentRequest['page'] = 1;
		}
        
        $nbPage = $this->getNbPage($currentRequest,1);
        
        $paginate = $this->getPaginate($nbPage);
        
        // Si on a des critères de recherche, on les applique en indiquant qu'on est dans une recherche
        if($currentRequest['search']){
            $this->ficheRepository->setSettings($this->settings,array('search' => 1));

			// On affiche la liste des fiches, les critère de recherche sont contenus dans $currentRequest
			$fiches = $this->ficheRepository->findWithSettings($currentRequest);
			$totalFiches = $this->ficheRepository->findWithSettings($currentRequest,1);

			//Adrien : créé le cache avec cette recherche pour cette session (flèche suivant/précédant en détail Elus front) ::class.user_backlink
			//TODOO : modifier pour permettre de mettre en cache plusieurs param de recherche pour un même utilisateur
			//créer un tableau 'search' dans la session utilisateur. chaque entrée du tableau aura un id spécifique à la recherche. Chaque Id aura une entrée en BDD
			$fichesRawResult = $this->ficheRepository->findWithSettings($currentRequest,0,TRUE);
			
			$ficheKkache = array();

			foreach($fichesRawResult as $cacheFiche){
				$ficheKkache[] = $cacheFiche['uid'];
			}

			//obligé d'envoyer un cookie pour qu'il garde mon id de session
			$GLOBALS['TSFE']->fe_user->fetchSessionData();
			$GLOBALS['TSFE']->fe_user->setKey('ses', 'test', array('keepit'=>1));
			$GLOBALS['TSFE']->fe_user->storeSessionData();
			$sesId = $GLOBALS['TSFE']->fe_user->id;
			\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager')->getCache('txeannuaires_search_cache')->set($sesId, $ficheKkache, array(), 3600);
			
			if($this->settings['organigramme']['chef']){
				$organigramme = $this->generateOrganigramme($fiches);
			}  
        }        
		$this->view->assign('totalFiches',$totalFiches);
		$this->view->assign('countFiches',$paginate);
		$this->view->assign('nbPage',$nbPage);
		$this->view->assign('currentPage',intval($currentRequest['page']));
		$this->view->assign('nextPage',intval($currentRequest['page']+1));
		$this->view->assign('prevPage',intval($currentRequest['page']-1));
		$this->view->assign('fiches', $fiches);
		$this->view->assign('searchObjet', $currentRequest['search']);
        $this->view->assign('organigramme', $organigramme);
		
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'BeforeRenderView', array($fiches, $this));
    }

	/**
	 * action show
	 *
	 * @param \Emagineurs\EAnnuaires\Domain\Model\Fiche $fiche
	 * @return void
	 */
	public function showAction(\Emagineurs\EAnnuaires\Domain\Model\Fiche $fiche = NULL) {
        if($fiche == NULL){
            if(!empty($this->settings['filtre']['whichMember']['value'])){
                $uid = explode(',',$this->settings['filtre']['whichMember']['value']);
                $uidUsed = $uid[0];

                $fiche = $this->ficheRepository->findByUid($uidUsed);
            } 
        }

		if($fiche == NULL){
			return 'Aucune fiche de l\'annuaire n\'a été sélectionnée!';
		} else {
			// On passe également l'url du site car dans les player les adresses relatives se basent sur le dossier du player et pas sur le baseurl
			$siteUrl = \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SITE_URL');

			$currentRequest = $this->request->getArguments();
					
			$this->settings['siteUrl'] = $siteUrl; 
			
			if($this->settings['confAbecedaire'][$fiche->getTypeelement()]){
				$method = 'get'.ucFirst($this->settings['confAbecedaire'][$fiche->getTypeelement()]);
				$this->titleTag($fiche->$method());
			} else {
				$this->titleTag($fiche->getTitle());
			}
			
			$this->view->assign('currentRequest', $currentRequest);
			$this->view->assign('fiche', $fiche);
			$this->view->assign('settings', $this->settings);
		}
		
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'BeforeRenderView', array($fiche, $this));
	}
    
	public function titleTag($titlePage){
		$content = trim($titlePage);
		if (!empty($content)) {
			$GLOBALS['TSFE']->page['title'] = $content;
			$GLOBALS['TSFE']->indexedDocTitle = $content;
		}
	}

	/**
	 * action new
	 *
	 * @return void
	 */
	public function catmenuAction() {
        if($this->settings['filtre']['categories']['value']){
            $cats = explode(',',$this->settings['filtre']['categories']['value']);
            $categories = $this->categorieRepository->findFromSettings($cats);
        } else {
            $categories = $this->categorieRepository->findCatFirstLvl();
        }
        
		$this->view->assign('categories', $categories);
		
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'BeforeRenderView', array($categories, $this));
	}
    
    public function TSinPlugin(){
        $flexformTyposcript = $this->settings['myTS'];

        if ($flexformTyposcript) {
            $tsparser = $this->objectManager->get('TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser');

            // Parse the new Typoscript
            $tsparser->parse($flexformTyposcript);

            // On enleve les "points" dans les clés du tableau
            $tsparser->setup = \Emagineurs\EAnnuaires\Utility\GeneralUtility::removeDotsInConf($tsparser->setup);
            
            // Copy the resulting setup back into conf
            \TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule($this->settings,$tsparser->setup);
        }
    }	
    
    /**
     * sendMail action
     *
     * @return void
     */
    public function sendMailAction(\Emagineurs\EAnnuaires\Domain\Model\Fiche $fiche = NULL) {
        $currentRequest = $this->request->getArguments();
		$sendObjet = $this->objectManager->get('Emagineurs\EAnnuaires\Domain\Model\Send');
    		
        // On récupère l'état à partir du paramètre activeSend, qui indique s'il faut affiché le formulaire ou envoyé le mail
		$etat = ($currentRequest['activeSend'] == '1') ? 'send' : 'form';
					
		// On envoi le mail si $etat vaut 'send'
         //   \t3lib_utility_Debug::debug($fiche,'TEST fiche');
        if($etat == 'send'){
            $destinataire = (!empty($currentRequest['customExpediteur'])) ? $currentRequest['customExpediteur'] : '';
			
			$this->sendMail($fiche,$currentRequest['fields'],$destinataire);
		}

		// on assigne ensuite les valeurs au template de la vue
		// Si on est dans l'etat form on affiche le formulaire, sinon on affiche que le mail a bien été envoyé
		// Note : dans l'idéal il faudra s'assurer que l'envoi de mail a effectivement fonctionné afin d'adapté le message de la vue avec l'etat send.
		$this->view->assign('fields', $currentRequest['fields']);
		$this->view->assign('etat', $etat);
		$this->view->assign('sendObjet', $sendObjet);
		$this->view->assign('fiche', $fiche);
		
		// Génère les tableaux configuré dans les settings via l'option properties
		$assignProperties = $this->getArrayToAssignFromProperties();
		
		$this->view->assignMultiple($assignProperties);
		
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'BeforeRenderView', array($assignProperties, $this));
	}
	
	public function getArrayToAssignFromProperties($arrayToAssign){ 
		$arrayToAssign = array();
		
		// On récupère les propriétés définis dans les settings
		$properties = $this->settings['sendMail']['properties'];

		// Pour chaque propriété on génère le tableau correspondant puis on l'inclut dans le tableau afin qu'il soit assigné à la vue par la suite
		foreach($properties as $key => $property){
			if(is_array($property)){
				if(array_key_exists('array',$property)){
					$currentArray = $this->utility->getArrayForSearchForm($property['array']);

					$arrayToAssign[$key] = $currentArray;
				}
			}
		}
		
		return $arrayToAssign;
	}
	
    public function sendMail($fiche,$fields,$destinataire=''){                
        //On crée le contenu du mail et du sujet
		$assign = array(
			'fields' => $fields,
			'fiche' => $fiche,
			'siteUrl' => \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SITE_URL'),
			'settings' => $this->settings
		);
		
		// Le chemin pour le mail et le sujet peut soit être donnée sous la form du chemin du template soit en entrant directant le code du template
		// Le sujet dispose d'un textarea dans le flexform prévu à cet effet, pour le mail il est préferable d'utiliser un template externe (plus simple mais techniquement, passé directement le code fonctionne aussi)
		// mail
        $mailPath = ($this->settings['sendMail']['templateMail']) ? $this->settings['sendMail']['templateMail'] : 'EXT:e_annuaires/Private/Templates/Custom/SendMail.html'; 
		
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'BeforeGenerateMailContent', array($mailPath, $assign, $this));
        $contenuMail = \Emagineurs\EAnnuaires\Utility\GeneralUtility::createTemplateFluid($mailPath,$assign);

		// sujet
        $subjectPath = $this->settings['sendMail']['sujetMail']; 
        $sujetMail = \Emagineurs\EAnnuaires\Utility\GeneralUtility::createTemplateFluid($subjectPath,$assign,1);

        // On configure les différentes adresses mail
		$mailExpediteurMail = $this->settings['sendMail']['mailExpediteur'];
        $nomExpediteurMail = $this->settings['sendMail']['nomExpediteur'];
        $destinataireMail = ($destinataire != '') ? $destinataire : explode(',',$this->settings['sendMail']['destinataireMail']);

		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'BeforeSend', array($destinataireMail, $mailExpediteurMail, $nomExpediteurMail, $sujetMail, $this));
		
		// On prépare le mail puis on l'envoie
        $this->mail = $this->objectManager->get('TYPO3\CMS\Core\Mail\MailMessage');
        $this->mail->setTo($destinataireMail)
            ->setFrom(array($mailExpediteurMail => $nomExpediteurMail))
            ->setSubject($sujetMail)
            ->setReturnPath($mailExpediteurMail)
            ->setCharset($GLOBALS['TSFE']->metaCharset)
            ->setReplyTo(array($mailExpediteurMail => $nomExpediteurMail));
	
        $this->mail->setBody($contenuMail, 'text/html');
		
        $this->mail->send();
    }
	
}
?>