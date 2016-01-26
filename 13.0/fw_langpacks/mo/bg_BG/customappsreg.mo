��    &      L  5   |      P     Q     d     {     �  f   �  W   �  �   U     �          %     8     O  1  c     �     �     �  �  �  9   �	  W   �	  2   I
     |
     �
     �
     �
     �
     �
  O   �
  $   +  0   P  a   �  _   �     C  �   I  �        �  �   �  �   �      %   *  *   P  /   {  
   �  �   �  �   o  !    /   9     i     �  !   �     �    �  "   �  $     "   <  �  _  d   �  �   Q  s   �     n     {  7   �  2   �  7   �     /  �   F  a   �  I   A   �   �   �   !     �!  �  �!  A  <#     ~$    �$    �%           &                  	                    
                          !                       #                                                   $               "          %    (pick destination) Add Custom Destination Add Custom Extension Admin Brief Description that will be published to modules when showing destinations. Example: My Weather App Brief description that will be published in the Extension Registry about this extension Choose un-identified destinations on your system to add to the Custom Destination Registry. This will insert the chosen entry into the Custom Destination box above. Conflicting Extensions Custom Applications Custom Destination Custom Destination: %s Custom Destinations Custom Destinations allows you to register your custom destinations that point to custom dialplans and will also 'publish' these destinations as available destinations to other modules. This is an advanced feature and should only be used by knowledgeable users. If you are getting warnings or errors in the notification panel about CUSTOM destinations that are correct, you should include them here. The 'Unknown Destinations' chooser will allow you to choose and insert any such destinations that the registry is not aware of into the Custom Destination field. Custom Extension Custom Extension:  Custom Extensions Custom Extensions provides you with a facility to register any custom extensions or feature codes that you have created in a custom file and FreePBX doesn't otherwise know about them. This allows the Extension Registry to be aware of your own extensions so that it can detect conflicts or report back information about your custom extensions to other modules that may make use of the information. You should not put extensions that you create in the Misc Apps Module as those are not custom. DUPLICATE Destination: This destination is already in use DUPLICATE Destination: This destination is in use or potentially used by another module DUPLICATE Extension: This extension already in use Delete Description Destination Quick Pick Edit Custom Destination Edit Custom Extension Edit:  Invalid Destination, must not be blank, must be formatted as: context,exten,pri Invalid Extension, must not be blank Invalid description specified, must not be blank More detailed notes about this destination to help document it. This field is not used elsewhere. More detailed notes about this extension to help document it. This field is not used elsewhere. Notes READONLY WARNING: Because this destination is being used by other module objects it can not be edited. You must remove those dependencies in order to edit this destination, or create a new destination to use Registry to add custom extensions and destinations that may be created and used so that the Extensions and Destinations Registry can include these. Submit Changes This is the Custom Destination to be published. It should be formatted exactly as you would put it in a goto statement, with context, exten, priority all included. An example might look like:<br />mycustom-app,s,1 This is the Extension or Feature Code you are using in your dialplan that you want the FreePBX Extension Registry to be aware of. Project-Id-Version: FreePBX v2.5
Report-Msgid-Bugs-To: 
POT-Creation-Date: 2015-02-20 19:15-0500
PO-Revision-Date: 2014-07-21 16:13+0200
Last-Translator: Chavdar <chavdar_75@yahoo.com>
Language-Team: Bulgarian <http://git.freepbx.org/projects/freepbx/customappsreg/bg/>
MIME-Version: 1.0
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 8bit
Language: bg_BG
Plural-Forms: nplurals=2; plural=n != 1;
X-Generator: Weblate 1.10-dev
X-Poedit-Language: Bulgarian
X-Poedit-Country: BULGARIA
X-Poedit-SourceCharset: utf-8
 (избери направление) Добави Custom Направление Добави Custom Вътрешна Линия Админ Кратко описание което ще се предостави на модулите когато показват направленията. Например: My Weather App Кратко описание което ще се представи в Регистрирани Вътрешни Линии за тази вътрешна линия Избира незвестни направления във вашата система за да ги добави към Регистрирани Custom Направления. Това ще постави избраното в Custom Направление полето по-горе. Вътрешни Линии в Конфликт Custom Приложения Custom Направление Custom Направление: %s Custom Направления Custom Направления ви дават възможността да регистрирате вашите custom направления, които да насочват към custom схеми на избиране и да 'предоставят' тези направления като възможни направления за другите модули. Това е сложна функция и трябва да се използва само от знаещи потребители. Ако получавате предупреждения и грешки в панела за системен статус за CUSTOM Направления които са коректни, тогава би трябвало да ги включите тук. 'Бързо Избиране на Направление' ви предлага да изберете и добавите всички направления за които регистрите не са известени в Custom Направление полето. Custom Вътрешна Линия Custom Вътрешна Линия:  Custom Вътрешни Линии Custom Вътрешни Линии ви дават възможноста да регистрирате всички custom вътрешни линии или специални кодове които сте създали в custom файл и FreePBX няма как да рабере за тях. Това позволява на Регистрирани Вътрешни Линии да научи за тях, така че да може да открива конфликти или да връща информация за вашите custom вътрешни линии към другите модули, които евентуално могат да използват тази информация. Не трябва да поставяте вътрешните линии които сте създали в Модул Други Направления, тъй като те не са custom. ДУБЛИРАНО Направление: Направлението вече се използва ДУБЛИРАНО Направление: Направлението се използва или потенциално се използва от друг модул ДУБЛИРАНА Вътрешна Линия: Тази вътрешна линия вече се използва Изтрий Описание Бързо Избиране на Направление Редактирай Custom Направление Редактирай Custom Вътрешна Линия Редактирай:  Неправилно Направление, не може да е празно, трябва да е форматирано така: context,exten,pri Неправилна Вътрешна Линия, полето не може да е празно Неправилно описание, не може да е празно По детайлни бележки за това направление. Това поле не се използва никъде. По детайлни бележки за тази вътрешна линия. Това поле не се използва никъде. Бележки ВНИМАНИЕ: Тъй като това направление се използва от друг модул не може да бъде редактирано. Трябва да премахните тази завимост за да можете да редактирате направлението или създайте ново направление което да използвате Предоставя възможност за добавяне на вътрешни линии и направления, които могат да се създават и използват така, че Регистратора на Вътрешни Линии и Направления да ги използва. Приеми Промените Custom Направление за предоставяне. Трябва да е форматирано точно както бихте го написали в goto въвеждане, с включени context, exten, priority. Например:<br />mycustom-app,s,1 Вътрешна Линия или Специален Код които използвате в вашите схеми на избиране за които искате FreePBX Регистрирани Вътрешни Линии да се известява. 