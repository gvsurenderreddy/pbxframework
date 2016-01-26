��    .      �  =   �      �  [   �  \   M  U   �  )      @   *     k  �   l  �  4     �     �  9   �     	  
   	     	  	   0	     :	     H	     X	     p	  7   ~	  9   �	  =   �	     .
     �
  $   �
  '   �
          #  .   ,  
   [     f     u     �  
   �     �     �  &   �  �   �  1   Z     �  :   �  P   �  &        F     R  �  Z  �   Z  �     �   �  >   �  �   �  #  z  !  �  �  �     �  2   �  \   �  
   =     H  !   ]          �     �  )   �     �  [     Z   h  e   �  O  )  )   y  D   �  Y   �  -   B     p  e   �     �     �  %        3     :  *   T       :   �  �   �  U   �      �   i   �   �   Z!  M   �!     H"     _"                           +      (   &                .      )   "   %                         !   
                           ,                #   -               	   $   '                  *                          It executes an HTTP GET passing the caller number as argument to retrieve the correct name  It executes an HTTPS GET passing the caller number as argument to retrieve the correct name  Use DNS to lookup caller names, it uses ENUM lookup zones as configured in enum.conf  Use OpenCNAM [https://www.opencnam.com/]  use astdb as lookup source, use phonebook module to populate it <p><b>NOTE:</b> OpenCNAM's Hobbyist Tier (default) only allows you to do 60 cached CallerID lookups per hour. If you get more than 60 incoming calls per hour, or want real-time CallerID information (more accurate), you should use the Professional Tier.</p> <p>If you'd like to create an OpenCNAM Professional Tier account, you can do so on their website: <a href="https://www.opencnam.com/register" target="_blank">https://www.opencnam.com/register</a></p> A Lookup Source let you specify a source for resolving numeric CallerIDs of incoming calls, you can then link an Inbound route to a specific CID source. This way you will have more detailed CDR reports with information taken directly from your CRM. You can also install the phonebook module to have a small number <-> name association. Pay attention, name lookup may slow down your PBX Actions Add CIDLookup Source Adding opencnam account columns to the cidlookup table... Admin Auth Token CID Lookup Source CIDLookup Cache Results CallerID Lookup CallerID Lookup Sources Character Set Checking for cidlookup field in core's incoming table.. Cleaning up duplicate OpenCNAM CallerID Lookup Sources... Could not add opencnam_account_sid column to cidlookup table. Decide whether or not cache the results to astDB; it will overwrite present values. It does not affect Internal source behavior ERROR: failed:  Enter a description for this source. FATAL: failed to transform old routes:  Host name or IP address Internal Migrating channel routing to Zap DID routing.. MySQL Host MySQL Password MySQL Username None Not Needed Not yet implemented OK Password to use in HTTP authentication Query, special token '[NUMBER]' will be replaced with caller number<br/>e.g.: SELECT name FROM phonebook WHERE number LIKE '%[NUMBER]%' Removing deprecated channel field from incoming.. Source Sources can be added in Caller Name Lookup Sources section There are %s DIDs using this source that will no longer have lookups if deleted. Username to use in HTTP authentication not present removed Project-Id-Version: 1.3
Report-Msgid-Bugs-To: 
POT-Creation-Date: 2015-11-24 12:53-0800
PO-Revision-Date: 2015-05-31 00:21+0200
Last-Translator: Yuriy <alliancesko@gmail.com>
Language-Team: Russian <http://weblate.freepbx.org/projects/freepbx/cidlookup/ru_RU/>
MIME-Version: 1.0
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 8bit
Language: ru_RU
Plural-Forms: nplurals=3; plural=n%10==1 && n%100!=11 ? 0 : n%10>=2 && n%10<=4 && (n%100<10 || n%100>=20) ? 1 : 2;
X-Generator: Weblate 2.2-dev
  это выполняет HTTP GET запрос, передавая номер звонящего в качестве аргумента, чтобы получить правильное имя  это выполняет HTTPS GET запрос, передавая номер звонящего в качестве аргумента, чтобы получить правильное имя  Использовать DNS для поиска имен абонентов, он использует ENUM зоны поиска, как настроено в enum.conf  Использовать OpenCNAM [https://www.opencnam.com/]  использовать astdb как источник поиска, воспользуйтесь модулем телефонной книги для заполнения <p> <b> Примечание: </b> OpenCNAM уровня Hobbyist (по умолчанию) позволяет сделать только 60 операций поиска в кэше CallerID в час. Если вы получаете больше, чем 60 входящих вызовов в час, или вы хотите получать информацию CallerID в реальном времени (более точный способ), вы должны использовать профессиональный уровень. </p> <p>если вы хотите создать учетную запись OpenCNAM профессионального уровня, вы можете сделать это на сайте: <a href="https://www.opencnam.com/register" target="_blank">https://www.opencnam.com/register</a></p> Сервис поиска по Caller ID поможет превращать поступающие звонки из номеров в узнаваемые имена или названия, которые затем можно сопоставлять со сценариями входящей маршрутизации для каждого. Ещё одно преимущество - более понятный и детальный список входящих в отчетах о звонках, с добавлением информации прямо из вашей программы CRM. Также можно инсталлировать и использовать модуль Телефонная книга для сопоставления коротких номеров и имен. Внимание! Сервис поиска может затормаживать быстродействие вашей ИП-АТС, если её ресурсы скромны Действия Добавить источник поиска CID Добавление колонок opencnam аккаунта к таблице cidlookup... Админ Авт. маркер Источник поиска CID Поиск CID Результаты кеша Поиск по Caller ID Источники поиска Caller ID Набор символов Проверка поля cidlookup в структуре таблицы входящих.. Очистка дубликатов источников поиска OpenCNAM CallerID... Не удалось добавить столбец opencnam_account_sid в таблицу cidlookup. Определитесь, нужно ли кешировать результаты запросов в astDB; результаты кеш могут не всегда совпадать с действительными. Не влияет на поведение и достоверность внутренних источников ОШИБКА: не получилось:  Создайте краткое описание источника. НЕ СУДЬБА: ошибка при переносе старых маршрутов:  Имя хоста или его IP адрес Внутренний Перенос маршрутизации каналов в маршрутизацию по Zap DID.. Хост MySQL Пароль MySQL Имя пользователя MySQL Нет Не надобности Пока не обеспечивается ОК Пароль для аутентификации по HTTP Строка запроса, содержащая '[NUMBER]', которая получает значение Caller ID <br/>например: SELECT name FROM phonebook WHERE number LIKE '%[NUMBER]%' Удаление устаревшего поля канала из входящих.. Источник Источник может быть добавлен в секцию Сервис поиска Caller ID Следующие номера DID %s не смогут больше использовать этот источник если он будет удалён. Имя пользователя для аутентификации по HTTP отсутствует удалено 