Extension Manual
=================

TEST GERRIT 3 - ssh connection

This is a template manual aiming to pave the way to developers when it comes about documentation. The template provides a structure that a developer can take over and, in addition, many useful snippets and examples. Documentation is written in reST format. Refer to Help writing reStructuredText to get some more insight about the syntax and available reST editors. For instance, you might be particularly interested how you can :

* generate the documentation using on-line services (@todo to write) 
* `make links`_ accros projects
* how you should write TypoScript reference.

Any idea or suggestion for improving this template `can be drop`_ to our team_. And remember: documentation is like gift wrapping, it looks like superfluous, but your friend tends to be rather disappointed when their presents arrive in supermarket carrier bags. (Documentation-Driven Design quote)

.. _can be drop: http://forge.typo3.org/projects/typo3v4-official_extension_template/issues
.. _team: http://forge.typo3.org/projects/typo3v4-official_extension_template
.. _make links: RestructuredtextHelp.html#cross-linking
.. _can write TypoScript: RestructuredtextHelp.html#typoscript-reference


************---------- TSCONFIG POUR LES PARTIALS ----------************
Pour ajouter les partials au flexforms, l'extension réutilise le fonctionnement de news en modifié. Il faut les ajouter en TS config: 
tx_eannuaires.templateLayouts{
	liste{
		1 = 1er partial de la vue liste 
		2 = 2eme partial de la vue liste
	} 
	detail{
		1 = 1er partial de la vue detail 
		2 = 2eme partial de la vue detail
	} 
	catmenu{
		1 = 1er partial de la vue menu des catégorie 
		2 = 2eme partial de la vue menu des catégorie
	} 
	search{
		1 = 1er partial de la vue search 
		2 = 2eme partial de la vue search
	} 
	result{
		1 = 1er partial de la vue result 
		2 = 2eme partial de la vue result
	} 
	manage{
		1 = 1er partial de la vue manage 
		2 = 2eme partial de la vue manage
	} 
}

Les id de ces partails sont disponibles en fluid via les instructions suivantes : 
{settings.templateLayoutListe} 
{settings.templateLayoutDetail} 
{settings.templateLayoutCatmenu} 
{settings.templateLayoutSearch} 
{settings.templateLayoutResult} 
{settings.templateLayoutNew} 
{settings.templateLayoutEdit}

************---------- DEFINIR LES TYPES D'ANNUAIRES ----------************
Ca se fait dans la configuration de l'extension dans l'extension manager, il faut lister les extensions selon le schema : 1-Fiche par défaut,2-Type d'annuaire 2,numeroDeMonType-NomDeMoNType...

nb :
pas d'espace entre les virgules
pas d'espaces entre les tirets
espace possible dans le nom du type
le type devrait être disponible en front sous la forme {type.id} et {type.nom} (pas besoin de passer par le nom du champ "typeelement")
ne pas mettre d'id a 0, il ne sera pas pris en compte par le filtre par type du flexform

************---------- CONFIGURER LES CHAMPS ET LES LABELS SELON LE TYPE ----------************
Ca se fait dans la configuration de l'extension dans l'extension manager :

Un tableau avec la liste des types est automatiquement généré. La liste des champs dans la table des annuaires est affiché pour chaque type avec une case à cocher. Seuls les champs cochés seront affichés dans le TCA pour le type correspondant.
Sous les cases à cocher, on a un 1er champ texte qui permet de définir l'ordre dans lequel apparaissent les champs dans le TCA
Encore en dessous on a un champ texte qui permet de définir le label du champ. La valeur donnée correspond à l'index du fichier locallang_db.xlf

************---------- CONFIGURER LES FILTRES EN TS ----------************ 
e_annuaires permet de mettre en place des "filtres" pour les vues listes et recherche. Ces filtres sont configurables en typoscript et ont pour but de donner à l'extension une grande souplesse.
Ces settings sont présent dans les settings de l'extension. Par conséquent certaines des conf peuvent être défini dans le flexform comme dans un template ts.
Les filtres sont à définir dans settings.filtre et ont l'apparence suivante :
filtre{
	ficheType{											==> Nom du filtre, sans interet "technique" sert essentiellement à distinguer les filtres
		typeRequete = equals							==> Type de filtre, détermine comment le filtre doit être appliqué
		champFiltre = typeelement						==> Champ sur lequel se base le filtre, indique dans quelle champ on va cherche la valeur par laquelle on filtre
		table = tx_eannuaires_domain_model_fiche		==> Table contenant les enregistrement à filtrer
		caseSensitive = 0								==> Indique si la casse doit être prise en compte pour le filtrage, existe uniquement pour le typeRequete "equals"
		value = 2										==> Valeur que doit avoir le champ "champFiltre"
	}
}

DETAIL DES OPTIONS D'UN FILTRE : 

	- value / valueConf / specialValue :
		Ces trois propriétés permettent de définir la valeur qui sera utilisé pour le filtre. 
		# value > la valeur tell quelle à utiliser, peut soit être défini directement en typoscript, soit provenir d'un flexform si le champ s'appelait bien <settings.filtre.NomDuFiltre.value>
		# valueConf > c'est un stdWrap, il permet donc d'utiliser toutes les fonctionnalité du stdWrap pour déterminer la valeur a utilisé. Par exemple :
		filtre.NomDuFiltre.valueConf{
			  data = GP:tx_eannuaires_pi1|catFiltre
			  if.isTrue.data = GP:tx_eannuaires_pi1|catFiltre
		}
		Dans ce cas, la liste sera filtré selon la valeur passé dans le paramètre GET tx_eannuaires_pi1[catFiltre], si celui-ci est présent.
		A noter que valueConf est prioritaire sur value, par conséquent si une value est défini mais que sur un plugin on utilise valueConf, si le résultat n'est pas false, c'est valueConf qui sera utilisé. Par exemple dans l'exemple précédent, si une valeur est configuré par défaut dans le flexform, elle sera appliqué tant que le paramètre ne sera pas dans l'url.
		#specialValue > permet de récupérer un résultat de requête SQL. La requete est un simple SELECT, la requête a effecter est défini par les paramètres specialValue.specialField, specialValue.specialTable et value.
		Si specialValue est défini, la conf value deviens la valeur du where sur lequel s'applique le stdWrap specialValue.specialValue.
		Par exemple, on peut écrire :
		filtre.NomDuFiltre{
			typeRequete = in
			champFiltre = canton
			table = tx_eannuaires_domain_model_fiche
			specialValue{
				specialField = cantons
				specialTable = tx_enews_domain_model_commune
				specialValue.data = GP:tx_eannuaires_pi1|search|communes
				specialValue.wrap = uid = |
			}
		}
		
	- typeRequete :
		Cette configuration permet de définir comment la valeur doit être comparé au champ spécifié. Il y a plusieurs valeurs valeurs possibles qui correspondent au type d'opérateur disponible en extbase. Ceux-ci sont : equals, in, contains, like, lessThan, lessThanOrEqual, greaterThan, greaterThanOrEqual. On a en plus le type "typeCat" qui a été fait "a la main" pour des besoins plus avancées (par exemple les catégories)
		
		#equals > vérifie que la valeur passé est exactement identique au champ défini. Cela implique, par exemple que si un champ a 2 catégorie et qu'on fait un equals sur le champ des catégories, même si une des deux est bonne, la fiche ne remontera pas car ce n'est pas exactement identique.
		
		#in > permet de comparer si on a plusieurs valeurs. Pas trop de différence avec contains, mais quelques fois in a provoqué des erreurs typo que contains a corrigé, donc plutot utilisé contains, mais si ca ne marche pas, essayer avec in (astuce très pointue)
	
		#contains > Comme in permet de comparer a plusieurs valeur, en théorie pose moins souvent de problème.
		
		#like > identique au champ like de SQL. Permet de verifier la présence d'un chaine de caractère à l'aide du caractère % . Le % remplace n'importe quel caractère en n'importe quel nombre. Par exemple un like = plop% renverra toute les fiches dont le contenu commence par "plop", car il recherche la chaine indiqué (plop) suivi de n'importe quoi. %plop permettra de récuperer tous enregistrements dont le champ fini par plop. %plop% retournera tous les enregistrements qui contiennent plop quelque par dans le champ indiqué.
		
		#lessThan > Verfie si le champ est strictement inférieur à la valeur que l'on compare.
		
		#lessThanOrEqual > Verfie si le champ est inférieur ou égal à la valeur que l'on compare.
		
		#greaterThan > Verfie si le champ est strictement supérieur à la valeur que l'on compare.
		
		#greaterThanOrEqual > Verfie si le champ est supérieur à la valeur que l'on compare.
		
		#typeCat > type particulier permettant de définir plus de critère. Permet de choisir si on souhaite avoir au moins une des valeurs dans le champs, toutes les valeurs dans le champs ou exactement les même valeurs que le champs. Le fonctionnement a appliqué est défini par la conf "andOr" : 
		filtre.NomDuFiltre.andOr = 1 > au moins une des valeurs passée est dans le champ
		filtre.NomDuFiltre.andOr = 2 > toutes les valeurs passées sont dans le champ
		filtre.NomDuFiltre.andOr = 3 > les valeurs passés correspondent exactement a celles du champ

		#between > Verfie si le champ est compris entre deux valeures. Définir le value de l'input de la sorte '0<500'
		Si une seule valeure est rentrée dans un between, on sera dans le cas 'greaterThanOrEqual' 
		
	- parentField / recursive / selectFields
		Ces confs sont disponibles pour tous les types de requêtes sauf les "contains". Elle permettent des récuperer les enregistrements enfants de ceux récupérer de base par la requête. 
		
		#ParentField permet d'indiquer le champ parent, c'est-à-dire celui qui devra avoir la valeur des résultat de la reqête au niveau 0.
		#recursive indique le niveau de récursivité
		#selectFields les champs a remonté pour les enfants (par défaut uid).
		
		Ces confs sont notamment utilisé pour le filtre par pid et celui pas catégory. Dans les 2 cas on a la conf recursive qui provient du flexform et qui est simplement un nombre, la conf parentField est défini dans le template statique de l'extension, ici s'agit du champ pid pour les pid et du champ parent pour les catégories. Le selectFields n'est jamais rempli car on n'utilise uniquement des uid.
		
	- champFiltre 
		Il s'agit simplement du champ que l'on va comparer à la valeur passée pour filtrer les fiches. N'importe quel champ de la base de donnée convient.
		
	- table
		Il s'agit de la table dans laquelle on va chercher les enregistrements
		
	- caseSensitive
		Disponible uniquement avec le type de requête equals, permet de définir si comparaison avec le champ passé doit prendre la casse en compte.
		

************---------- LISTE DES HOOKS ----------************
> Dans ficheController.php :
	- listActionBeforeRenderView
	- searchActionBeforeRenderView
	- resultatActionBeforeRenderView
	- showActionBeforeRenderView
	- catmenuActionBeforeRenderView
	- manageActionBeforeRenderView
	- newActionBeforeRenderView
	- listFicheUserActionBeforeRenderView
	- editActionBeforeRenderView
	- createActionBeforeAdd
	- createActionBeforeRedirect
	- updateActionBeforeUpdate
	- updateActionBeforeRedirect
	- sendMailActionBeforeRenderView
	- sendMailBeforeGenerateMailContent
	- sendMailBeforeSend

