��    -      �  =   �      �     �     �     �          (     8  W   F  �   �     C     Z     q  1  �     �     �     �  �  �  2   �	     
     
     
     +
     B
  ~   O
     �
  	   �
  O   �
  $   /  0   T     �     �  a   �  _        p     s     y           �     �     �  D   �  �   �  �   �  v   R     �  �  �     �     �  &   �     �             ]   1  �   �     ~     �     �  �  �     �     �     �  C  �  /     	   B     L     X      d     �  �   �  	   :     D  o   J  &   �  =   �          ?  y   V  r   �     C     G     M     \  (   e  	   �     �  M   �    �  �   	  �   �          '                  ,   +      	       (       #                                 "      -            !          )      &                                  *   
                                              %   $    (pick destination) Actions Add Custom Destination Add Custom Extension Add Destination Add Extension Brief description that will be published in the Extension Registry about this extension Choose un-identified destinations on your system to add to the Custom Destination Registry. This will insert the chosen entry into the Custom Destination box above. Conflicting Extensions Custom Destination: %s Custom Destinations Custom Destinations allows you to register your custom destinations that point to custom dialplans and will also 'publish' these destinations as available destinations to other modules. This is an advanced feature and should only be used by knowledgeable users. If you are getting warnings or errors in the notification panel about CUSTOM destinations that are correct, you should include them here. The 'Unknown Destinations' chooser will allow you to choose and insert any such destinations that the registry is not aware of into the Custom Destination field. Custom Extension Custom Extension:  Custom Extensions Custom Extensions provides you with a facility to register any custom extensions or feature codes that you have created in a custom file and FreePBX doesn't otherwise know about them. This allows the Extension Registry to be aware of your own extensions so that it can detect conflicts or report back information about your custom extensions to other modules that may make use of the information. You should not put extensions that you create in the Misc Apps Module as those are not custom. DUPLICATE Extension: This extension already in use Delete Description Destination Destination Quick Pick Destinations Does this destination end with 'Return'? If so, you can then select a subsequent destination after this call flow is complete. Edit:  Extension Invalid Destination, must not be blank, must be formatted as: context,exten,pri Invalid Extension, must not be blank Invalid description specified, must not be blank List Custom Extensions List Destinations More detailed notes about this destination to help document it. This field is not used elsewhere. More detailed notes about this extension to help document it. This field is not used elsewhere. No Notes Reset Return Set the Destination after return Submit Target The entered extension conflicts with another extension on the system This is the Custom Destination to be published. It should be formatted exactly as you would put it in a goto statement, with context, exten, priority all included. An example might look like:<br />mycustom-app,s,1 This is the Extension or Feature Code you are using in your dialplan that you want the FreePBX Extension Registry to be aware of. WARNING: This destination is being used by other module objects. Changing this destination may cause unexpected issue. Yes Project-Id-Version: PACKAGE VERSION
Report-Msgid-Bugs-To: 
POT-Creation-Date: 2016-04-04 11:34-0700
PO-Revision-Date: 2016-02-04 23:06+0200
Last-Translator: Nicolas Riendeau <freepbx@riendeau.org>
Language-Team: French <http://weblate.freepbx.org/projects/freepbx/customappsreg/fr_FR/>
MIME-Version: 1.0
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 8bit
Language: fr_FR
Plural-Forms: nplurals=2; plural=n > 1;
X-Generator: Weblate 2.2-dev
 (sélectionnez une destiation) Actions Ajouter une destination personnalisée Ajouter un poste personnalisé Ajouter une destination Ajouter un poste Brève description qui sera publiée dans le registre de poste sur de ce poste téléphonique Choisissez des destinations non identifiées sur votre système pour les ajouter au registre de destinations personnalisées. Cela aura pour effet d'insérer l'entrée sélectionnée dans la boite 'Destinations personnalisées' ci-dessus. Postes Conflictuels Destination personnalisée : %s Destinations personnalisées Les destinations personnalisées vous permettent d'enregistrer vos destinations pointant sur des plans de numérotation personnalisés et 'publieront' également ces destinations en tant que destinations disponibles pour d'autres modules. Il s'agit d'une fonctionnalité avancée qui devrait seulement être utlisée par les utlisateurs experimentés. Si vous recevez des avertissements ou des erreurs dans le panneau de notifications à propos de destinations PERSONNALISÉES correctes, vous devriez les inclure ici. Le sélecteur de 'Destinations inconnues' vous permet de choisir et d'insérer des destinations dont le registre ne connait pas l'existence dans le champ 'Destination personnalisée'. Poste personnalisé Poste personnalisée :  Poste personnalisés Les postes personnalisées vous permettent d'enregistrer facilement toutes sortes de poste personnalisés ou de fonctions que vous avez créé dans un fichier personnalisé et que FreePBX ne pourrait pas prendre en compte autrement. Cela permet au registre de poste de connaître vos propres poste afin qu'il puisse détecter des conflits ou reporter aux autres modules des informations à propos de vos poste personnalisés que ces modules pourraient utiliser. Vous ne devriez pas mettre de poste que vous avez créé dans le Module Misc Apps car ils ne sont pas personnalisés. Poste DUPLIQUÉ : Cet poste est déjà utilisé Supprimer Description Destination Sélection rapide de destination Destinations Est-ce que cette destination se termine avec un 'Return'? Si c'est le cas, vous pouvez choisir qu'elle sera la destination suivante après que l'appel soit complété. Editer :  Poste Destination invalide. Elle ne doit pas être vide et doit être formatté comme suit : contexte,poste,priorité Poste invalide, ne doit pas être vide Description spécifiée invalide, elle ne doit pas être vide Liste des postes personnalisés Liste des destinations Notes plus détaillées à propos de cette destination pour aider à la documenter. Ce champ n'est pas utilisé ailleurs. Notes plus détaillées à propos de ce poste pour aider à le documenter. Ce champ ne sera pas utilisé ailleurs. Non Notes Réinitialiser Retourne Définis la destination après le retour Soumettre Destination Le numéro de poste fourni est en conflit avec un autre poste sur le système Il s'agit de la destination personnalisée à publier. Elle devrait être formatée de la même façon que si vous deviez la placer dans une déclaration goto, avec contexte, poste et priorité inclus. Voici un exemple de ce à quoi cela doit ressembler : <br />mycustom-app,s,1 Il s'agit du poste ou code de fonction que vous utilisez dans votre plan de numérotation et dont vous voulez informer le registre de poste. ATTENTION : Cette destination est utilisée ailleurs. Si vous changez cette destination, cela pourrait avoir des résultats inattendus. Oui 