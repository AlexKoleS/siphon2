var search_data = {
    'index': {
        'searchIndex': ["icecave","icecave\\siphon","icecave\\siphon\\atom","icecave\\siphon\\exception","icecave\\siphon\\player","icecave\\siphon\\schedule","icecave\\siphon\\score","icecave\\siphon\\score\\boxscore","icecave\\siphon\\score\\livescore","icecave\\siphon\\team","icecave\\siphon\\atom\\atomentryinterface","icecave\\siphon\\atom\\atomreaderinterface","icecave\\siphon\\atom\\atomresultinterface","icecave\\siphon\\exception\\serviceunavailableexception","icecave\\siphon\\factory","icecave\\siphon\\factoryinterface","icecave\\siphon\\packageinfo","icecave\\siphon\\player\\injuryinterface","icecave\\siphon\\player\\playerinterface","icecave\\siphon\\player\\playerreaderinterface","icecave\\siphon\\player\\seasondetailsinterface","icecave\\siphon\\player\\statisticsinterface","icecave\\siphon\\player\\statisticsreaderinterface","icecave\\siphon\\schedule\\competitioncollectioninterface","icecave\\siphon\\schedule\\competitioninterface","icecave\\siphon\\schedule\\competitionstatus","icecave\\siphon\\schedule\\scheduleinterface","icecave\\siphon\\schedule\\schedulelimit","icecave\\siphon\\schedule\\schedulereaderinterface","icecave\\siphon\\schedule\\seasoninterface","icecave\\siphon\\score\\boxscore\\boxscorereaderinterface","icecave\\siphon\\score\\boxscore\\boxscoreresultinterface","icecave\\siphon\\score\\inninginterface","icecave\\siphon\\score\\inningscoreinterface","icecave\\siphon\\score\\inningstatisticsinterface","icecave\\siphon\\score\\livescore\\inningresultinterface","icecave\\siphon\\score\\livescore\\inningsubtype","icecave\\siphon\\score\\livescore\\livescorereaderinterface","icecave\\siphon\\score\\livescore\\livescoreresultinterface","icecave\\siphon\\score\\livescore\\periodresultinterface","icecave\\siphon\\score\\livescore\\scopestatus","icecave\\siphon\\score\\periodinterface","icecave\\siphon\\score\\periodtype","icecave\\siphon\\score\\scopeinterface","icecave\\siphon\\score\\scoreinterface","icecave\\siphon\\score\\teamscoreinterface","icecave\\siphon\\team\\teaminterface","icecave\\siphon\\team\\teamreaderinterface","icecave\\siphon\\urlbuilderinterface","icecave\\siphon\\xmlreaderinterface","icecave\\siphon\\atom\\atomentryinterface::url","icecave\\siphon\\atom\\atomentryinterface::updatedtime","icecave\\siphon\\atom\\atomreaderinterface::read","icecave\\siphon\\atom\\atomresultinterface::add","icecave\\siphon\\atom\\atomresultinterface::remove","icecave\\siphon\\atom\\atomresultinterface::entries","icecave\\siphon\\atom\\atomresultinterface::isempty","icecave\\siphon\\atom\\atomresultinterface::updatedtime","icecave\\siphon\\factory::create","icecave\\siphon\\factory::__construct","icecave\\siphon\\factory::urlbuilder","icecave\\siphon\\factory::xmlreader","icecave\\siphon\\factory::createatomreader","icecave\\siphon\\factory::createschedulereader","icecave\\siphon\\factory::createteamreader","icecave\\siphon\\factory::createlivescorereader","icecave\\siphon\\factoryinterface::urlbuilder","icecave\\siphon\\factoryinterface::xmlreader","icecave\\siphon\\factoryinterface::createatomreader","icecave\\siphon\\factoryinterface::createschedulereader","icecave\\siphon\\factoryinterface::createteamreader","icecave\\siphon\\factoryinterface::createlivescorereader","icecave\\siphon\\player\\injuryinterface::playerid","icecave\\siphon\\player\\injuryinterface::season","icecave\\siphon\\player\\injuryinterface::number","icecave\\siphon\\player\\injuryinterface::position","icecave\\siphon\\player\\injuryinterface::positionname","icecave\\siphon\\player\\injuryinterface::isactive","icecave\\siphon\\player\\playerinterface::id","icecave\\siphon\\player\\playerinterface::firstname","icecave\\siphon\\player\\playerinterface::lastname","icecave\\siphon\\player\\playerinterface::displayname","icecave\\siphon\\player\\playerreaderinterface::read","icecave\\siphon\\player\\seasondetailsinterface::playerid","icecave\\siphon\\player\\seasondetailsinterface::season","icecave\\siphon\\player\\seasondetailsinterface::number","icecave\\siphon\\player\\seasondetailsinterface::position","icecave\\siphon\\player\\seasondetailsinterface::positionname","icecave\\siphon\\player\\seasondetailsinterface::isactive","icecave\\siphon\\player\\statisticsinterface::playerid","icecave\\siphon\\player\\statisticsinterface::season","icecave\\siphon\\player\\statisticsinterface::get","icecave\\siphon\\player\\statisticsinterface::gettotal","icecave\\siphon\\player\\statisticsinterface::has","icecave\\siphon\\player\\statisticsinterface::getiterator","icecave\\siphon\\player\\statisticsinterface::iterate","icecave\\siphon\\player\\statisticsinterface::iteratebygroup","icecave\\siphon\\player\\statisticsinterface::iteratebykey","icecave\\siphon\\player\\statisticsreaderinterface::read","icecave\\siphon\\schedule\\competitioncollectioninterface::competitions","icecave\\siphon\\schedule\\competitioncollectioninterface::isempty","icecave\\siphon\\schedule\\competitioninterface::id","icecave\\siphon\\schedule\\competitioninterface::status","icecave\\siphon\\schedule\\competitioninterface::starttime","icecave\\siphon\\schedule\\competitioninterface::sport","icecave\\siphon\\schedule\\competitioninterface::league","icecave\\siphon\\schedule\\competitioninterface::hometeamid","icecave\\siphon\\schedule\\competitioninterface::awayteamid","icecave\\siphon\\schedule\\scheduleinterface::add","icecave\\siphon\\schedule\\scheduleinterface::remove","icecave\\siphon\\schedule\\scheduleinterface::seasons","icecave\\siphon\\schedule\\schedulereaderinterface::read","icecave\\siphon\\schedule\\schedulereaderinterface::readdeleted","icecave\\siphon\\schedule\\seasoninterface::id","icecave\\siphon\\schedule\\seasoninterface::name","icecave\\siphon\\schedule\\seasoninterface::startdate","icecave\\siphon\\schedule\\seasoninterface::enddate","icecave\\siphon\\schedule\\seasoninterface::add","icecave\\siphon\\schedule\\seasoninterface::remove","icecave\\siphon\\score\\boxscore\\boxscorereaderinterface::read","icecave\\siphon\\score\\boxscore\\boxscoreresultinterface::competitionscore","icecave\\siphon\\score\\inningstatisticsinterface::hometeamhits","icecave\\siphon\\score\\inningstatisticsinterface::awayteamhits","icecave\\siphon\\score\\inningstatisticsinterface::hometeamerrors","icecave\\siphon\\score\\inningstatisticsinterface::awayteamerrors","icecave\\siphon\\score\\livescore\\inningresultinterface::currentinningsubtype","icecave\\siphon\\score\\livescore\\livescorereaderinterface::read","icecave\\siphon\\score\\livescore\\livescoreresultinterface::currentscope","icecave\\siphon\\score\\livescore\\livescoreresultinterface::currentscopestatus","icecave\\siphon\\score\\livescore\\livescoreresultinterface::competitionstatus","icecave\\siphon\\score\\livescore\\livescoreresultinterface::competitionscore","icecave\\siphon\\score\\livescore\\periodresultinterface::currentgametime","icecave\\siphon\\score\\periodinterface::type","icecave\\siphon\\score\\scoreinterface::add","icecave\\siphon\\score\\scoreinterface::remove","icecave\\siphon\\score\\scoreinterface::scopes","icecave\\siphon\\score\\teamscoreinterface::hometeamscore","icecave\\siphon\\score\\teamscoreinterface::awayteamscore","icecave\\siphon\\team\\teaminterface::id","icecave\\siphon\\team\\teaminterface::name","icecave\\siphon\\team\\teaminterface::nickname","icecave\\siphon\\team\\teaminterface::abbreviation","icecave\\siphon\\team\\teaminterface::displayname","icecave\\siphon\\team\\teamreaderinterface::read","icecave\\siphon\\urlbuilderinterface::build","icecave\\siphon\\urlbuilderinterface::extract","icecave\\siphon\\xmlreaderinterface::read"],
        'info': [["Icecave","","Icecave.html","","",3],["Icecave\\Siphon","","Icecave\/Siphon.html","","",3],["Icecave\\Siphon\\Atom","","Icecave\/Siphon\/Atom.html","","",3],["Icecave\\Siphon\\Exception","","Icecave\/Siphon\/Exception.html","","",3],["Icecave\\Siphon\\Player","","Icecave\/Siphon\/Player.html","","",3],["Icecave\\Siphon\\Schedule","","Icecave\/Siphon\/Schedule.html","","",3],["Icecave\\Siphon\\Score","","Icecave\/Siphon\/Score.html","","",3],["Icecave\\Siphon\\Score\\BoxScore","","Icecave\/Siphon\/Score\/BoxScore.html","","",3],["Icecave\\Siphon\\Score\\LiveScore","","Icecave\/Siphon\/Score\/LiveScore.html","","",3],["Icecave\\Siphon\\Team","","Icecave\/Siphon\/Team.html","","",3],["AtomEntryInterface","Icecave\\Siphon\\Atom","Icecave\/Siphon\/Atom\/AtomEntryInterface.html","","A single Atom result.",1],["AtomReaderInterface","Icecave\\Siphon\\Atom","Icecave\/Siphon\/Atom\/AtomReaderInterface.html","","A client for SDI Atom feeds.",1],["AtomResultInterface","Icecave\\Siphon\\Atom","Icecave\/Siphon\/Atom\/AtomResultInterface.html","","The result of reading an Atom feed.",1],["ServiceUnavailableException","Icecave\\Siphon\\Exception","Icecave\/Siphon\/Exception\/ServiceUnavailableException.html"," < RuntimeException","Indicates that the feed service is currently unavailable.",1],["Factory","Icecave\\Siphon","Icecave\/Siphon\/Factory.html","","The global factory used to create Siphon feed readers.",1],["FactoryInterface","Icecave\\Siphon","Icecave\/Siphon\/FactoryInterface.html","","The global factory used to create Siphon feed readers.",1],["PackageInfo","Icecave\\Siphon","Icecave\/Siphon\/PackageInfo.html","","",1],["InjuryInterface","Icecave\\Siphon\\Player","Icecave\/Siphon\/Player\/InjuryInterface.html","","Encapsulates season-specific information about a player.",1],["PlayerInterface","Icecave\\Siphon\\Player","Icecave\/Siphon\/Player\/PlayerInterface.html","","A player, an athelete who participates in a competition.",1],["PlayerReaderInterface","Icecave\\Siphon\\Player","Icecave\/Siphon\/Player\/PlayerReaderInterface.html","","Read data from player feeds.",1],["SeasonDetailsInterface","Icecave\\Siphon\\Player","Icecave\/Siphon\/Player\/SeasonDetailsInterface.html","","Encapsulates season-specific information about a player.",1],["StatisticsInterface","Icecave\\Siphon\\Player","Icecave\/Siphon\/Player\/StatisticsInterface.html","","Player statistics.",1],["StatisticsReaderInterface","Icecave\\Siphon\\Player","Icecave\/Siphon\/Player\/StatisticsReaderInterface.html","","Read data from player statistics feeds.",1],["CompetitionCollectionInterface","Icecave\\Siphon\\Schedule","Icecave\/Siphon\/Schedule\/CompetitionCollectionInterface.html","","A collection of competitions.",1],["CompetitionInterface","Icecave\\Siphon\\Schedule","Icecave\/Siphon\/Schedule\/CompetitionInterface.html","","A sports competition.",1],["CompetitionStatus","Icecave\\Siphon\\Schedule","Icecave\/Siphon\/Schedule\/CompetitionStatus.html"," < AbstractEnumeration","The status of a competition.",1],["ScheduleInterface","Icecave\\Siphon\\Schedule","Icecave\/Siphon\/Schedule\/ScheduleInterface.html","","A schedule is a collection of seasons.",1],["ScheduleLimit","Icecave\\Siphon\\Schedule","Icecave\/Siphon\/Schedule\/ScheduleLimit.html"," < AbstractEnumeration","A sporting schedule.",1],["ScheduleReaderInterface","Icecave\\Siphon\\Schedule","Icecave\/Siphon\/Schedule\/ScheduleReaderInterface.html","","A client for SDI schedule feeds.",1],["SeasonInterface","Icecave\\Siphon\\Schedule","Icecave\/Siphon\/Schedule\/SeasonInterface.html","","A season within a schedule.",1],["BoxScoreReaderInterface","Icecave\\Siphon\\Score\\BoxScore","Icecave\/Siphon\/Score\/BoxScore\/BoxScoreReaderInterface.html","","A client for reading SDI box score feeds.",1],["BoxScoreResultInterface","Icecave\\Siphon\\Score\\BoxScore","Icecave\/Siphon\/Score\/BoxScore\/BoxScoreResultInterface.html","","The result of reading a box score feed.",1],["InningInterface","Icecave\\Siphon\\Score","Icecave\/Siphon\/Score\/InningInterface.html","","A scope for sports that use innings (baseball).",1],["InningScoreInterface","Icecave\\Siphon\\Score","Icecave\/Siphon\/Score\/InningScoreInterface.html","","A competition score for sports that use innings (baseball).",1],["InningStatisticsInterface","Icecave\\Siphon\\Score","Icecave\/Siphon\/Score\/InningStatisticsInterface.html","","Additional statistics for innings-based sports.",1],["InningResultInterface","Icecave\\Siphon\\Score\\LiveScore","Icecave\/Siphon\/Score\/LiveScore\/InningResultInterface.html","","The result of reading a live score feed for a competition",1],["InningSubType","Icecave\\Siphon\\Score\\LiveScore","Icecave\/Siphon\/Score\/LiveScore\/InningSubType.html"," < AbstractEnumeration","A sub-type (half) of an inning.",1],["LiveScoreReaderInterface","Icecave\\Siphon\\Score\\LiveScore","Icecave\/Siphon\/Score\/LiveScore\/LiveScoreReaderInterface.html","","A client for reading SDI live score feeds.",1],["LiveScoreResultInterface","Icecave\\Siphon\\Score\\LiveScore","Icecave\/Siphon\/Score\/LiveScore\/LiveScoreResultInterface.html","","The result of reading a live score feed.",1],["PeriodResultInterface","Icecave\\Siphon\\Score\\LiveScore","Icecave\/Siphon\/Score\/LiveScore\/PeriodResultInterface.html","","The result of reading a live score feed for a competition",1],["ScopeStatus","Icecave\\Siphon\\Score\\LiveScore","Icecave\/Siphon\/Score\/LiveScore\/ScopeStatus.html"," < AbstractEnumeration","",1],["PeriodInterface","Icecave\\Siphon\\Score","Icecave\/Siphon\/Score\/PeriodInterface.html","","A scope used for period-based sports (anything that",1],["PeriodType","Icecave\\Siphon\\Score","Icecave\/Siphon\/Score\/PeriodType.html"," < AbstractEnumeration","",1],["ScopeInterface","Icecave\\Siphon\\Score","Icecave\/Siphon\/Score\/ScopeInterface.html","","A scope within a competition.",1],["ScoreInterface","Icecave\\Siphon\\Score","Icecave\/Siphon\/Score\/ScoreInterface.html","","A competition score.",1],["TeamScoreInterface","Icecave\\Siphon\\Score","Icecave\/Siphon\/Score\/TeamScoreInterface.html","","A score pair for a team-based sport.",1],["TeamInterface","Icecave\\Siphon\\Team","Icecave\/Siphon\/Team\/TeamInterface.html","","A team in a team sport.",1],["TeamReaderInterface","Icecave\\Siphon\\Team","Icecave\/Siphon\/Team\/TeamReaderInterface.html","","Read data from team feeds.",1],["UrlBuilderInterface","Icecave\\Siphon","Icecave\/Siphon\/UrlBuilderInterface.html","","Constructs a fully-qualified feed URL from a resource",1],["XmlReaderInterface","Icecave\\Siphon","Icecave\/Siphon\/XmlReaderInterface.html","","Read XML data based on a resource name.",1],["AtomEntryInterface::url","Icecave\\Siphon\\Atom\\AtomEntryInterface","Icecave\/Siphon\/Atom\/AtomEntryInterface.html#method_url","()","Get the URL of the feed that has been updated.",2],["AtomEntryInterface::updatedTime","Icecave\\Siphon\\Atom\\AtomEntryInterface","Icecave\/Siphon\/Atom\/AtomEntryInterface.html#method_updatedTime","()","Get the time at which the update occurred.",2],["AtomReaderInterface::read","Icecave\\Siphon\\Atom\\AtomReaderInterface","Icecave\/Siphon\/Atom\/AtomReaderInterface.html#method_read","(<abbr title=\"Icecave\\Chrono\\DateTime\">DateTime<\/abbr> $threshold, string|null $feed = null, integer $limit = 5000, integer $order = SORT_ASC)","Fetch a list of feeds that have been updated since",2],["AtomResultInterface::add","Icecave\\Siphon\\Atom\\AtomResultInterface","Icecave\/Siphon\/Atom\/AtomResultInterface.html#method_add","(<a href=\"Icecave\/Siphon\/Atom\/AtomEntryInterface.html\"><abbr title=\"Icecave\\Siphon\\Atom\\AtomEntryInterface\">AtomEntryInterface<\/abbr><\/a> $entry)","Add an entry to the collection.",2],["AtomResultInterface::remove","Icecave\\Siphon\\Atom\\AtomResultInterface","Icecave\/Siphon\/Atom\/AtomResultInterface.html#method_remove","(<a href=\"Icecave\/Siphon\/Atom\/AtomEntryInterface.html\"><abbr title=\"Icecave\\Siphon\\Atom\\AtomEntryInterface\">AtomEntryInterface<\/abbr><\/a> $entry)","Remove an entry from the collection.",2],["AtomResultInterface::entries","Icecave\\Siphon\\Atom\\AtomResultInterface","Icecave\/Siphon\/Atom\/AtomResultInterface.html#method_entries","()","Get an array of the entries.",2],["AtomResultInterface::isEmpty","Icecave\\Siphon\\Atom\\AtomResultInterface","Icecave\/Siphon\/Atom\/AtomResultInterface.html#method_isEmpty","()","Indicates whether or not the collection contains any",2],["AtomResultInterface::updatedTime","Icecave\\Siphon\\Atom\\AtomResultInterface","Icecave\/Siphon\/Atom\/AtomResultInterface.html#method_updatedTime","()","Get the time at which the atom result was produced.",2],["Factory::create","Icecave\\Siphon\\Factory","Icecave\/Siphon\/Factory.html#method_create","(string $apiKey)","Create a factory that uses the given API key.",2],["Factory::__construct","Icecave\\Siphon\\Factory","Icecave\/Siphon\/Factory.html#method___construct","(<a href=\"Icecave\/Siphon\/UrlBuilderInterface.html\"><abbr title=\"Icecave\\Siphon\\UrlBuilderInterface\">UrlBuilderInterface<\/abbr><\/a> $urlBuilder, <a href=\"Icecave\/Siphon\/XmlReaderInterface.html\"><abbr title=\"Icecave\\Siphon\\XmlReaderInterface\">XmlReaderInterface<\/abbr><\/a> $xmlReader)","",2],["Factory::urlBuilder","Icecave\\Siphon\\Factory","Icecave\/Siphon\/Factory.html#method_urlBuilder","()","Get the URL builder used by the factory.",2],["Factory::xmlReader","Icecave\\Siphon\\Factory","Icecave\/Siphon\/Factory.html#method_xmlReader","()","Get the XML reader used by the factory.",2],["Factory::createAtomReader","Icecave\\Siphon\\Factory","Icecave\/Siphon\/Factory.html#method_createAtomReader","()","Create an atom reader.",2],["Factory::createScheduleReader","Icecave\\Siphon\\Factory","Icecave\/Siphon\/Factory.html#method_createScheduleReader","()","Create a schedule reader.",2],["Factory::createTeamReader","Icecave\\Siphon\\Factory","Icecave\/Siphon\/Factory.html#method_createTeamReader","()","Create a team reader.",2],["Factory::createLiveScoreReader","Icecave\\Siphon\\Factory","Icecave\/Siphon\/Factory.html#method_createLiveScoreReader","()","Create a live score reader.",2],["FactoryInterface::urlBuilder","Icecave\\Siphon\\FactoryInterface","Icecave\/Siphon\/FactoryInterface.html#method_urlBuilder","()","Get the URL builder used by the factory.",2],["FactoryInterface::xmlReader","Icecave\\Siphon\\FactoryInterface","Icecave\/Siphon\/FactoryInterface.html#method_xmlReader","()","Get the XML reader used by the factory.",2],["FactoryInterface::createAtomReader","Icecave\\Siphon\\FactoryInterface","Icecave\/Siphon\/FactoryInterface.html#method_createAtomReader","()","Create an atom reader.",2],["FactoryInterface::createScheduleReader","Icecave\\Siphon\\FactoryInterface","Icecave\/Siphon\/FactoryInterface.html#method_createScheduleReader","()","Create a schedule reader.",2],["FactoryInterface::createTeamReader","Icecave\\Siphon\\FactoryInterface","Icecave\/Siphon\/FactoryInterface.html#method_createTeamReader","()","Create a team reader.",2],["FactoryInterface::createLiveScoreReader","Icecave\\Siphon\\FactoryInterface","Icecave\/Siphon\/FactoryInterface.html#method_createLiveScoreReader","()","Create a live score reader.",2],["InjuryInterface::playerId","Icecave\\Siphon\\Player\\InjuryInterface","Icecave\/Siphon\/Player\/InjuryInterface.html#method_playerId","()","Get the player ID.",2],["InjuryInterface::season","Icecave\\Siphon\\Player\\InjuryInterface","Icecave\/Siphon\/Player\/InjuryInterface.html#method_season","()","Get the season name.",2],["InjuryInterface::number","Icecave\\Siphon\\Player\\InjuryInterface","Icecave\/Siphon\/Player\/InjuryInterface.html#method_number","()","Get the player's jersey number for this season.",2],["InjuryInterface::position","Icecave\\Siphon\\Player\\InjuryInterface","Icecave\/Siphon\/Player\/InjuryInterface.html#method_position","()","Get the player's position for this season.",2],["InjuryInterface::positionName","Icecave\\Siphon\\Player\\InjuryInterface","Icecave\/Siphon\/Player\/InjuryInterface.html#method_positionName","()","Get the player's position name.",2],["InjuryInterface::isActive","Icecave\\Siphon\\Player\\InjuryInterface","Icecave\/Siphon\/Player\/InjuryInterface.html#method_isActive","()","Indicates whether or not the player is active on the",2],["PlayerInterface::id","Icecave\\Siphon\\Player\\PlayerInterface","Icecave\/Siphon\/Player\/PlayerInterface.html#method_id","()","Get the player ID.",2],["PlayerInterface::firstName","Icecave\\Siphon\\Player\\PlayerInterface","Icecave\/Siphon\/Player\/PlayerInterface.html#method_firstName","()","Get the player's first name.",2],["PlayerInterface::lastName","Icecave\\Siphon\\Player\\PlayerInterface","Icecave\/Siphon\/Player\/PlayerInterface.html#method_lastName","()","Get the player's last name.",2],["PlayerInterface::displayName","Icecave\\Siphon\\Player\\PlayerInterface","Icecave\/Siphon\/Player\/PlayerInterface.html#method_displayName","()","Get the player's display name.",2],["PlayerReaderInterface::read","Icecave\\Siphon\\Player\\PlayerReaderInterface","Icecave\/Siphon\/Player\/PlayerReaderInterface.html#method_read","(string $sport, string $league, string $season, string $teamId)","Read a player feed.",2],["SeasonDetailsInterface::playerId","Icecave\\Siphon\\Player\\SeasonDetailsInterface","Icecave\/Siphon\/Player\/SeasonDetailsInterface.html#method_playerId","()","Get the player ID.",2],["SeasonDetailsInterface::season","Icecave\\Siphon\\Player\\SeasonDetailsInterface","Icecave\/Siphon\/Player\/SeasonDetailsInterface.html#method_season","()","Get the season name.",2],["SeasonDetailsInterface::number","Icecave\\Siphon\\Player\\SeasonDetailsInterface","Icecave\/Siphon\/Player\/SeasonDetailsInterface.html#method_number","()","Get the player's jersey number for this season.",2],["SeasonDetailsInterface::position","Icecave\\Siphon\\Player\\SeasonDetailsInterface","Icecave\/Siphon\/Player\/SeasonDetailsInterface.html#method_position","()","Get the player's position for this season.",2],["SeasonDetailsInterface::positionName","Icecave\\Siphon\\Player\\SeasonDetailsInterface","Icecave\/Siphon\/Player\/SeasonDetailsInterface.html#method_positionName","()","Get the player's position name.",2],["SeasonDetailsInterface::isActive","Icecave\\Siphon\\Player\\SeasonDetailsInterface","Icecave\/Siphon\/Player\/SeasonDetailsInterface.html#method_isActive","()","Indicates whether or not the player is active on the",2],["StatisticsInterface::playerId","Icecave\\Siphon\\Player\\StatisticsInterface","Icecave\/Siphon\/Player\/StatisticsInterface.html#method_playerId","()","Get the player ID.",2],["StatisticsInterface::season","Icecave\\Siphon\\Player\\StatisticsInterface","Icecave\/Siphon\/Player\/StatisticsInterface.html#method_season","()","Get the season name.",2],["StatisticsInterface::get","Icecave\\Siphon\\Player\\StatisticsInterface","Icecave\/Siphon\/Player\/StatisticsInterface.html#method_get","(string $group, string $key, mixed $default)","Get the value for specific statistical metric.",2],["StatisticsInterface::getTotal","Icecave\\Siphon\\Player\\StatisticsInterface","Icecave\/Siphon\/Player\/StatisticsInterface.html#method_getTotal","(string $key)","Get the total value for a specific statistical metric",2],["StatisticsInterface::has","Icecave\\Siphon\\Player\\StatisticsInterface","Icecave\/Siphon\/Player\/StatisticsInterface.html#method_has","(string $group, string $key)","Check if a given key exists within a group.",2],["StatisticsInterface::getIterator","Icecave\\Siphon\\Player\\StatisticsInterface","Icecave\/Siphon\/Player\/StatisticsInterface.html#method_getIterator","()","Iterate over statistical totals.",2],["StatisticsInterface::iterate","Icecave\\Siphon\\Player\\StatisticsInterface","Icecave\/Siphon\/Player\/StatisticsInterface.html#method_iterate","()","Iterate over a flat sequence of groups and keys.",2],["StatisticsInterface::iterateByGroup","Icecave\\Siphon\\Player\\StatisticsInterface","Icecave\/Siphon\/Player\/StatisticsInterface.html#method_iterateByGroup","()","Iterate over the the statistics by group.",2],["StatisticsInterface::iterateByKey","Icecave\\Siphon\\Player\\StatisticsInterface","Icecave\/Siphon\/Player\/StatisticsInterface.html#method_iterateByKey","()","Iterate over the the statistics by key.",2],["StatisticsReaderInterface::read","Icecave\\Siphon\\Player\\StatisticsReaderInterface","Icecave\/Siphon\/Player\/StatisticsReaderInterface.html#method_read","(string $sport, string $league, string $season, string $teamId)","Read a player statistics feed.",2],["CompetitionCollectionInterface::competitions","Icecave\\Siphon\\Schedule\\CompetitionCollectionInterface","Icecave\/Siphon\/Schedule\/CompetitionCollectionInterface.html#method_competitions","()","Get the competitions in the collection.",2],["CompetitionCollectionInterface::isEmpty","Icecave\\Siphon\\Schedule\\CompetitionCollectionInterface","Icecave\/Siphon\/Schedule\/CompetitionCollectionInterface.html#method_isEmpty","()","Indicates whether or not the schedule contains any",2],["CompetitionInterface::id","Icecave\\Siphon\\Schedule\\CompetitionInterface","Icecave\/Siphon\/Schedule\/CompetitionInterface.html#method_id","()","Get the competition ID.",2],["CompetitionInterface::status","Icecave\\Siphon\\Schedule\\CompetitionInterface","Icecave\/Siphon\/Schedule\/CompetitionInterface.html#method_status","()","Get the competition status.",2],["CompetitionInterface::startTime","Icecave\\Siphon\\Schedule\\CompetitionInterface","Icecave\/Siphon\/Schedule\/CompetitionInterface.html#method_startTime","()","Get the time at which the competition begins.",2],["CompetitionInterface::sport","Icecave\\Siphon\\Schedule\\CompetitionInterface","Icecave\/Siphon\/Schedule\/CompetitionInterface.html#method_sport","()","Get the sport.",2],["CompetitionInterface::league","Icecave\\Siphon\\Schedule\\CompetitionInterface","Icecave\/Siphon\/Schedule\/CompetitionInterface.html#method_league","()","Get the league.",2],["CompetitionInterface::homeTeamId","Icecave\\Siphon\\Schedule\\CompetitionInterface","Icecave\/Siphon\/Schedule\/CompetitionInterface.html#method_homeTeamId","()","Get the ID of the home team.",2],["CompetitionInterface::awayTeamId","Icecave\\Siphon\\Schedule\\CompetitionInterface","Icecave\/Siphon\/Schedule\/CompetitionInterface.html#method_awayTeamId","()","Get the ID of the away team.",2],["ScheduleInterface::add","Icecave\\Siphon\\Schedule\\ScheduleInterface","Icecave\/Siphon\/Schedule\/ScheduleInterface.html#method_add","(<a href=\"Icecave\/Siphon\/Schedule\/SeasonInterface.html\"><abbr title=\"Icecave\\Siphon\\Schedule\\SeasonInterface\">SeasonInterface<\/abbr><\/a> $season)","Add a season to the schedule.",2],["ScheduleInterface::remove","Icecave\\Siphon\\Schedule\\ScheduleInterface","Icecave\/Siphon\/Schedule\/ScheduleInterface.html#method_remove","(<a href=\"Icecave\/Siphon\/Schedule\/SeasonInterface.html\"><abbr title=\"Icecave\\Siphon\\Schedule\\SeasonInterface\">SeasonInterface<\/abbr><\/a> $season)","Remove a season from the schedule.",2],["ScheduleInterface::seasons","Icecave\\Siphon\\Schedule\\ScheduleInterface","Icecave\/Siphon\/Schedule\/ScheduleInterface.html#method_seasons","()","Get the seasons in the schedule.",2],["ScheduleReaderInterface::read","Icecave\\Siphon\\Schedule\\ScheduleReaderInterface","Icecave\/Siphon\/Schedule\/ScheduleReaderInterface.html#method_read","(string $sport, string $league, <a href=\"Icecave\/Siphon\/Schedule\/ScheduleLimit.html\"><abbr title=\"Icecave\\Siphon\\Schedule\\ScheduleLimit\">ScheduleLimit<\/abbr><\/a> $limit = null)","Read a schedule feed.",2],["ScheduleReaderInterface::readDeleted","Icecave\\Siphon\\Schedule\\ScheduleReaderInterface","Icecave\/Siphon\/Schedule\/ScheduleReaderInterface.html#method_readDeleted","(string $sport, string $league)","Read the deleted schedule feed.",2],["SeasonInterface::id","Icecave\\Siphon\\Schedule\\SeasonInterface","Icecave\/Siphon\/Schedule\/SeasonInterface.html#method_id","()","Get the season ID.",2],["SeasonInterface::name","Icecave\\Siphon\\Schedule\\SeasonInterface","Icecave\/Siphon\/Schedule\/SeasonInterface.html#method_name","()","Get the season name.",2],["SeasonInterface::startDate","Icecave\\Siphon\\Schedule\\SeasonInterface","Icecave\/Siphon\/Schedule\/SeasonInterface.html#method_startDate","()","Get the date on which the season starts.",2],["SeasonInterface::endDate","Icecave\\Siphon\\Schedule\\SeasonInterface","Icecave\/Siphon\/Schedule\/SeasonInterface.html#method_endDate","()","Get the date on which the season ends.",2],["SeasonInterface::add","Icecave\\Siphon\\Schedule\\SeasonInterface","Icecave\/Siphon\/Schedule\/SeasonInterface.html#method_add","(<a href=\"Icecave\/Siphon\/Schedule\/CompetitionInterface.html\"><abbr title=\"Icecave\\Siphon\\Schedule\\CompetitionInterface\">CompetitionInterface<\/abbr><\/a> $competition)","Add a competition to the season.",2],["SeasonInterface::remove","Icecave\\Siphon\\Schedule\\SeasonInterface","Icecave\/Siphon\/Schedule\/SeasonInterface.html#method_remove","(<a href=\"Icecave\/Siphon\/Schedule\/CompetitionInterface.html\"><abbr title=\"Icecave\\Siphon\\Schedule\\CompetitionInterface\">CompetitionInterface<\/abbr><\/a> $competition)","Remove a competition from the season.",2],["BoxScoreReaderInterface::read","Icecave\\Siphon\\Score\\BoxScore\\BoxScoreReaderInterface","Icecave\/Siphon\/Score\/BoxScore\/BoxScoreReaderInterface.html#method_read","(string $sport, string $league, string $season, string $competitionId)","Read a box score feed for a competition.",2],["BoxScoreResultInterface::competitionScore","Icecave\\Siphon\\Score\\BoxScore\\BoxScoreResultInterface","Icecave\/Siphon\/Score\/BoxScore\/BoxScoreResultInterface.html#method_competitionScore","()","Get the competition score.",2],["InningStatisticsInterface::homeTeamHits","Icecave\\Siphon\\Score\\InningStatisticsInterface","Icecave\/Siphon\/Score\/InningStatisticsInterface.html#method_homeTeamHits","()","Get the number of hits made by the home team.",2],["InningStatisticsInterface::awayTeamHits","Icecave\\Siphon\\Score\\InningStatisticsInterface","Icecave\/Siphon\/Score\/InningStatisticsInterface.html#method_awayTeamHits","()","Get the number of hits made by the away team.",2],["InningStatisticsInterface::homeTeamErrors","Icecave\\Siphon\\Score\\InningStatisticsInterface","Icecave\/Siphon\/Score\/InningStatisticsInterface.html#method_homeTeamErrors","()","Get the number of errors made by the home team.",2],["InningStatisticsInterface::awayTeamErrors","Icecave\\Siphon\\Score\\InningStatisticsInterface","Icecave\/Siphon\/Score\/InningStatisticsInterface.html#method_awayTeamErrors","()","Get the number of errors made by the away team.",2],["InningResultInterface::currentInningSubType","Icecave\\Siphon\\Score\\LiveScore\\InningResultInterface","Icecave\/Siphon\/Score\/LiveScore\/InningResultInterface.html#method_currentInningSubType","()","Get the current sub-type of the current inning.",2],["LiveScoreReaderInterface::read","Icecave\\Siphon\\Score\\LiveScore\\LiveScoreReaderInterface","Icecave\/Siphon\/Score\/LiveScore\/LiveScoreReaderInterface.html#method_read","(string $sport, string $league, string $competitionId)","Read a live score feed for a competition.",2],["LiveScoreResultInterface::currentScope","Icecave\\Siphon\\Score\\LiveScore\\LiveScoreResultInterface","Icecave\/Siphon\/Score\/LiveScore\/LiveScoreResultInterface.html#method_currentScope","()","Get the current scope, if any.",2],["LiveScoreResultInterface::currentScopeStatus","Icecave\\Siphon\\Score\\LiveScore\\LiveScoreResultInterface","Icecave\/Siphon\/Score\/LiveScore\/LiveScoreResultInterface.html#method_currentScopeStatus","()","Get the status of the current scope.",2],["LiveScoreResultInterface::competitionStatus","Icecave\\Siphon\\Score\\LiveScore\\LiveScoreResultInterface","Icecave\/Siphon\/Score\/LiveScore\/LiveScoreResultInterface.html#method_competitionStatus","()","Get the current status of the competition.",2],["LiveScoreResultInterface::competitionScore","Icecave\\Siphon\\Score\\LiveScore\\LiveScoreResultInterface","Icecave\/Siphon\/Score\/LiveScore\/LiveScoreResultInterface.html#method_competitionScore","()","Get the competition score.",2],["PeriodResultInterface::currentGameTime","Icecave\\Siphon\\Score\\LiveScore\\PeriodResultInterface","Icecave\/Siphon\/Score\/LiveScore\/PeriodResultInterface.html#method_currentGameTime","()","Get the current game time.",2],["PeriodInterface::type","Icecave\\Siphon\\Score\\PeriodInterface","Icecave\/Siphon\/Score\/PeriodInterface.html#method_type","()","Get the type of the period.",2],["ScoreInterface::add","Icecave\\Siphon\\Score\\ScoreInterface","Icecave\/Siphon\/Score\/ScoreInterface.html#method_add","(<a href=\"Icecave\/Siphon\/Score\/ScopeInterface.html\"><abbr title=\"Icecave\\Siphon\\Score\\ScopeInterface\">ScopeInterface<\/abbr><\/a> $scope)","Add a scope to the score.",2],["ScoreInterface::remove","Icecave\\Siphon\\Score\\ScoreInterface","Icecave\/Siphon\/Score\/ScoreInterface.html#method_remove","(<a href=\"Icecave\/Siphon\/Score\/ScopeInterface.html\"><abbr title=\"Icecave\\Siphon\\Score\\ScopeInterface\">ScopeInterface<\/abbr><\/a> $scope)","Remove a scope from the score.",2],["ScoreInterface::scopes","Icecave\\Siphon\\Score\\ScoreInterface","Icecave\/Siphon\/Score\/ScoreInterface.html#method_scopes","()","Get the scopes that make up the score.",2],["TeamScoreInterface::homeTeamScore","Icecave\\Siphon\\Score\\TeamScoreInterface","Icecave\/Siphon\/Score\/TeamScoreInterface.html#method_homeTeamScore","()","The home team's score.",2],["TeamScoreInterface::awayTeamScore","Icecave\\Siphon\\Score\\TeamScoreInterface","Icecave\/Siphon\/Score\/TeamScoreInterface.html#method_awayTeamScore","()","The away team's score.",2],["TeamInterface::id","Icecave\\Siphon\\Team\\TeamInterface","Icecave\/Siphon\/Team\/TeamInterface.html#method_id","()","Get the team ID.",2],["TeamInterface::name","Icecave\\Siphon\\Team\\TeamInterface","Icecave\/Siphon\/Team\/TeamInterface.html#method_name","()","Get the team name.",2],["TeamInterface::nickname","Icecave\\Siphon\\Team\\TeamInterface","Icecave\/Siphon\/Team\/TeamInterface.html#method_nickname","()","Get the team nick name.",2],["TeamInterface::abbreviation","Icecave\\Siphon\\Team\\TeamInterface","Icecave\/Siphon\/Team\/TeamInterface.html#method_abbreviation","()","Get the team short name.",2],["TeamInterface::displayName","Icecave\\Siphon\\Team\\TeamInterface","Icecave\/Siphon\/Team\/TeamInterface.html#method_displayName","()","Get the team display name.",2],["TeamReaderInterface::read","Icecave\\Siphon\\Team\\TeamReaderInterface","Icecave\/Siphon\/Team\/TeamReaderInterface.html#method_read","(string $sport, string $league, string $season)","Read a team feed.",2],["UrlBuilderInterface::build","Icecave\\Siphon\\UrlBuilderInterface","Icecave\/Siphon\/UrlBuilderInterface.html#method_build","(string $resource, array $parameters)","Build a feed URL.",2],["UrlBuilderInterface::extract","Icecave\\Siphon\\UrlBuilderInterface","Icecave\/Siphon\/UrlBuilderInterface.html#method_extract","(string $url)","Extract the resource and parameters from a fully-qualified",2],["XmlReaderInterface::read","Icecave\\Siphon\\XmlReaderInterface","Icecave\/Siphon\/XmlReaderInterface.html#method_read","(string $resource, array $parameters = array())","Read XML data for the given resource.",2]]
    }
}
search_data['index']['longSearchIndex'] = search_data['index']['searchIndex']