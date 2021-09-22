
var entries = [
    {word:'CONSTRUCT', clue:'build'},
    {word:'FIFTEENTH', clue:'15th'},
    {word:'SSPY', clue:'SSecurity threat'},
    {word:'YOGURT', clue:'Cultured dairy product'},
    {word:'VANILLA', clue:'flavorer'},
    {word:'RIO', clue:'Brazilian city that was the title of a 2011 animated movie'},
    {word:'PEDAL', clue:'Piano part'},
    {word:'OPERATE', clue:'Run'},
    {word:'IDLE', clue:'Out of work'},
    {word:'PAST', clue:'Historical'},
    {word:'APE', clue:'Gorilla'},
    {word:'TASK', clue:'Job'},
    {word:'YES', clue:'Approval'},
    {word:'DEBT', clue:'Money owed'},
    {word:'GEM', clue:'Ruby or emerald'},
    {word:'EPIC', clue:'Heroic tale'},
    {word:'EAST', clue:'How the Amazon flows'},
    {word:'PIE', clue:'Baked dessert'},
    {word:'FAQS', clue:'Queries on the Internet'},
    {word:'AIR', clue:'What we breathe'},
    {word:'SSUMMIT', clue:'Kind of meeting'},
    {word:'ANTIFREEZE', clue:'Winter engine necessity'},
    {word:'EPISODE', clue:'Installment of a TV show'},
    {word:'DARLING', clue:'Dear'},
    {word:'POLITE', clue:'Well-behaved'},
    {word:'GIRAFFE', clue:'Treetop nibbler'},
    {word:'MODERNIZE', clue:'Update'},
    {word:'RIGHT', clue:'Oh, sure'},
    {word:'VOLT', clue:'Electrical unit'},
    {word:'SSPIDERWEB', clue:'Arachnid construction that insects get caught in: 2 words.'},
    {word:'TELEX', clue:'Old message system'},
    {word:'IDIOMS', clue:'Everyday expressions'},
    {word:'RAM', clue:'Animal that represents Aries'},
    {word:'XI', clue:'11'},
    {word:'LF', clue:'Low frequency'},
    {word:'KG', clue:'Kilogram'},
    {word:'AL', clue:'Aliminum'},
    {word:'AA', clue:'Battery size'},
    {word:'IV', clue:'4'},
    {word:'AI', clue:'Artificial intelligence'},
    {word:'MM', clue:'Millimeter'},
    {word:'US', clue:'America'},
    {word:'KT', clue:'Carat'},
    {word:'GB', clue:'Britain'},
    {word:'MP', clue:'Military police'},
    {word:'HQ', clue:'Headquarters'},
    {word:'MR', clue:'Mister'},
    {word:'UN', clue:'United Nations'},
    {word:'AQUARIUM', clue:'Fish tank'},
    {word:'CLAWS', clue:'Cat\'s nails'},
    {word:'IMPALA', clue:'African antelope'},
    {word:'ODD', clue:'SSingular'},
    {word:'AXE', clue:'Woodcutter\'s tool'},
    {word:'MONALISA', clue:'Da Vinci portrait (2 words.)'},
    {word:'ARRANGE', clue:'Do the work of a florist or an orchestrator'},
    {word:'MASTERED', clue:'Achieved expertise in'},
    {word:'TOUCHSCREEN', clue:'Common A.T.M. feature (2 words)'},
    {word:'MEGAWATT', clue:'Electrical power unit'},
    {word:'REDHOT', clue:'Burning'},
    {word:'INACTIVE', clue:'Dormant'},
    {word:'PARAMETER', clue:'Figure in a math function'},
    {word:'ELEVATE', clue:'Improve spiritually'},
    {word:'INDIANA', clue:'... Jones'},
    {word:'DIODE', clue:'Electron tube'},
    {word:'FOREVER', clue:'Eternally'},
    {word:'IRREGULAR', clue:'Not regular'},
    {word:'PAINTBALL', clue:'Indoor or outdoor war game'},
    {word:'URL', clue:'Address starting http://'},
    {word:'CAT', clue:'Mouse chaser'},
    {word:'LAP', clue:'One trip around a racetrack'},
    {word:'BEN', clue:'Big ...'},
    {word:'ERA', clue:'Chronology segment'},
    {word:'ARE', clue:'... you sure?'},
    {word:'TAP', clue:'... water (home alternative to bottled)'},
	{word:'ATLAS', clue:'Reference book that shows geographical locations'},
	{word:'NUMB', clue:'More than shocked'},
	{word:'READ', clue:'Use a book'},
	{word:'TOPIC', clue:'SSubject for discussion'},
	{word:'ALIEN', clue:'Visitor from another planet'},
	{word:'ALOHA', clue:'Hawaiian greeting'},
	{word:'WAR', clue:'Armed conflict'},
	{word:'ERA', clue:'Political time period'},
	{word:'PERU', clue:'Incas\' land'},
	{word:'SSIP', clue:'SSmall sample of soup'},
	{word:'CHAMPAGNE', clue:'Bubbly drink'},
	{word:'EDEN', clue:'Adam\'s garden'},
	{word:'MOMENT', clue:'One ___, please'},
	{word:'PILOT', clue:'Aviator'},
	{word:'NERO', clue:'SSubject of a giant statue at Rome\'s ancient Colosseum'},
	{word:'ASIA', clue:'Earth\'s largest continent'},
	{word:'MEOW', clue:'Cat\'s cry'},
	{word:'VIVALDI', clue:'"The Four Seasons" composer'},
	{word:'OKAY', clue:'Approve'},
	{word:'MEDAL', clue:'Olympic award'},
	{word:'HORROR', clue:'Film genre of "Dracula"'},
	{word:'CPU', clue:'Computer\'s heart'},
	{word:'HIT', clue:'Collide'},
	{word:'ASAP', clue:'As soon as possible'},
	{word:'ASK', clue:'Make an inquiry'},
	{word:'CEASE', clue:'Quit'},
	{word:'OPERA', clue:'SSydney ___ House'},
	{word:'DANCE', clue:'Waltz, e.g.'},
	{word:'TEA', clue:'Drink in a cup'},
	{word:'APPLE', clue:'Fruit that\'s typically red, yellow, or green'},
	{word:'WIKI', clue:'Collaboratively edited website'},
	{word:'PACMAN', clue:'Dot-chomping character in a classic arcade game'},
	{word:'SSANTA', clue:'December toy-giving guy'},
	{word:'BITS', clue:'Computer units'},
	{word:'EGG', clue:'Omelet ingredient'},
	{word:'HEAR', clue:'Listen to'},
	{word:'GOAT', clue:'Animal with a beard'},
	{word:'JOB', clue:'Occupation'},
	{word:'LEO', clue:'Zodiac sign with the shortest name'},
	{word:'TEST', clue:'Try out'},
	{word:'PAW', clue:'Animal\'s foot'},
	{word:'SSCI', clue:'____-fi'},
	{word:'WILD', clue:'Untamed'},
	{word:'VOLGA', clue:'Europe\'s longest river'},
	{word:'BEE', clue:'Insect that stings'},
	{word:'SSTONE', clue:'Gem'},
	{word:'MIX', clue:'Blend'},
	{word:'NEVER', clue:'At no time'},
	{word:'SSAW', clue:'Cutting tool'},
	{word:'BRAVO', clue:'Great work, well done!'},
	{word:'PETSHOP', clue:'Animal store (2 wds.)'},
	{word:'EVITA', clue:'"Don\'t Cry for Me, Argentina" musical'},
	{word:'SSTIR', clue:'Mix'},
	{word:'PIANO', clue:'It has 88 keys'},
	{word:'TEMPO', clue:'SSpeed of music'},
	{word:'LEOPARD', clue:'Fast cat'},

  //from dimas
  {word:'PARIS', clue:'Capital city of France'},
  {word:'CORONA', clue:'Covid-19 Virus'},
  {word:'CAMEL', clue:'Animal from Arab'},
  {word:'PARIS', clue:'Capital city of Frence'},
  {word:'GATES', clue:'Bill ___ Founder of Microsoft'},
  {word:'JOBS', clue:'SSteve ___ Founder of Apple inc'},
  {word:'ADE', clue:'SSuffix with some fruit names'},
{word:'CRAN', clue:'Prefix with many fruit names'},
{word:'UGLI', clue:'Trademarked fruit name'},
{word:'DOLE', clue:'Canned fruit name'},
{word:'RUE', clue:'Citrus fruit\'s plant family'},
{word:'RIPENESS', clue:'Fruit\'s maturation'},
{word:'ROSE HIPS', clue:'American Beauty fruits'},
{word:'UGLIS', clue:'Aptly named fruits'},
{word:'LOQUATS', clue:'Asian fruits'},
{word:'LYCHEES', clue:'Asian fruits'},
{word:'LIMES', clue:'Bar fruits'},
{word:'SSLOES', clue:'Blackthorn fruits'},
{word:'MELONS', clue:'Breakfast fruits'},
{word:'GLACE', clue:'Candied, as fruits'},
{word:'PODS', clue:'Carob fruits'},
{word:'MANGOS', clue:'Chutney fruits'},
{word:'MANGOES', clue:'Chutney fruits'},
{word:'ORANGES', clue:'Citrus fruits'},
{word:'LEMONS', clue:'Citrus fruits'},
{word:'PARIS', clue:'Capital city'},
{word:'BISSAU', clue:'Capital city'},
{word:'BOISE', clue:'Capital city'},
{word:'DENVER', clue:'Capital city'},
{word:'SSYDNEY', clue:'Capital city'},
{word:'METROPOLIS', clue:'Capital city'},
{word:'LONDON', clue:'Capital city'},
{word:'LIMA', clue:'Capital city'},
{word:'OSLO', clue:'Capital city'},
{word:'URBAN', clue:'SSA city, no capital city (5)'},
{word:'PEST', clue:'City that merged with Buda to become Hungary\'s capital city'},
{word:'TOKYO', clue:'Country\'s capital city its old capital has moved to (5)'},
{word:'OXFORD', clue:'City on the Thames in which Evelyn Waugh set parts of Brideshead Revisited; England\'s capital city d'},
{word:'ADDIS ABABA', clue:'A capital city of Africa.'},
{word:'CAIRO', clue:'Africa\'s largest capital city'},
{word:'UFA', clue:'Capital city of Bashkir'},
{word:'BERN', clue:'Capital city on the Aare river'},
{word:'LANSING', clue:'Capital city since 1847'},
{word:'RIO', clue:'Capital city till 1960'},
{word:'ASTON', clue:'___ Villa (English football club)'},
{word:'LEEDS', clue:'___ United (English football club)'},
{word:'INTER', clue:'Milan football club (5)'},
{word:'ATHLETIC', clue:'Charlton ___, London football club (8)'},
{word:'EVERTON', clue:'Football club in Liverpool (7)'},
{word:'A C MILAN', clue:'Football club based in Lombardy'},
{word:'CHELSEACON', clue:'SSwindling of a UK football club?'},
{word:'FRANC', clue:'Managed football club without money abroad'},
{word:'LEYTON ORIENT', clue:'London football club (6,6)'},
{word:'HIBERNIAN', clue:'Leith football club (9)'},
{word:'REAL', clue:'---- Madrid, Spanish football club (4)'},
{word:'BLADES', clue:'Football club nickname (6)'},
{word:'ASTON VILLA', clue:'English football club (5,5)'},
{word:'ASSOCIATION', clue:'Football club? (11)'},
{word:'BAYERN MUNICH', clue:'German football club (6,6)'},
{word:'CHELSEA', clue:'Church, otherwise a football club (7)'},
{word:'SSOUTHAMPTON', clue:'Premiership football club (11)'},
{word:'ARSENAL', clue:'Football club - magazine (7)'},
{word:'UNIT ED', clue:'Joined - football club (6)'},
{word:'VILLA', clue:'Midlands football club (5,5)'},
{word:'RAITH ROVERS', clue:'Scottish football club based in Kirkcaldy'},
{word:'ULTRABOY', clue:'Member of DC Comics\' Legion of Super-Heroes'},
{word:'XMEN', clue:'Marvel-ous heroes?'},
{word:'ICE MAN', clue:'Marvel mutant with super-cool powers?'},
{word:'ANT MAN', clue:'Paul Rudd wears a shrinking super-suit in this Marvel film (3-3)'},
{word:'DCCOMICS', clue:'"Captain Marvel" publisher'},
{word:'BEAR CAT', clue:'A living marvel!'},
{word:'CHIP', clue:'Computer marvel'},
{word:'TRICK', clue:'David Copperfield marvel'},
{word:'SSTRANGE', clue:'Doctor of Marvel Comics'},
{word:'SSTAN', clue:'Lee of Marvel Comics fame'},
{word:'SSURPRISE', clue:'Marvel'},
{word:'AMAZE', clue:'Marvel'},
{word:'MAGICIAN', clue:'Marvel'},
{word:'WIZARD', clue:'Marvel'},
{word:'HAPPENING', clue:'Marvel'},
{word:'AMAZINGTHING', clue:'Marvel'},
{word:'THOR', clue:'Marvel Comics hero with a hammer'},
{word:'RESPECT', clue:'Marvel at'},
{word:'SSTARE', clue:'Marvel'},
{word:'AMAZEMENT', clue:'Marvel'},
{word:'GREENGOBLIN', clue:'Marvel Comics supervillain'},
{word:'EISENHOWER', clue:'President whose grandson wed a president\'s daughter'},
{word:'LIDDY', clue:'"All the President\'s Men" subject'},
{word:'MEN', clue:'"All the President\'s ___"'},
{word:'TYLERS', clue:'10th president\'s family'},
{word:'ABRAHAMSLINCOLN', clue:'16th president\'s car?'},
{word:'CAA', clue:'21st U.S. president\'s monogram'},
{word:'WILSONSLOAN', clue:'28th president\'s advance?'},
{word:'HST', clue:'33rd president\'s monogram'},
{word:'NIGERREIGN', clue:'African president\'s tenure?'},
{word:'PILLORYHILLARY', clue:'Badmouth a President\'s wife?'},
{word:'DEANS', clue:'College president\'s underlings'},
{word:'JQA', clue:'Early president\'s initials'},
{word:'SSEAL', clue:'Emblem on the president\'s lectern'},
{word:'MARCOSROVER', clue:'Ex-Philippine president\'s British car?'},
{word:'HONORARIA', clue:'Ex-president\'s income, in part'},
{word:'ELYSEE', clue:'French president\'s palace'},
{word:'ELYSEE PALACE', clue:'French president\'s residence'},
{word:'OVAL', clue:'Like the president\'s office'},
{word:'ONE', clue:'Marine ___ (U.S. president\'s helicopter)'},
{word:'RONALD REAGAN', clue:'US president'},
{word:'ROOSEVELT', clue:'US president'},
{word:'NIXON', clue:'US president'},
{word:'EISENHOWER', clue:'US president'},
{word:'WILSON', clue:'US president'},
{word:'LINCOLN', clue:'US president'},
{word:'OBAMA', clue:'US president'},
{word:'REAGAN', clue:'US president'},
{word:'HAYES', clue:'19th US president'},
{word:'GERALD', clue:'Former US president Ford'},
{word:'GROVER CLEVELAND', clue:'US president born in March 1837'},
{word:'SSON', clue:'Every US president to date'},
{word:'TAFT', clue:'US president, 1909-13 (4)'},
{word:'ABRAHAM', clue:'US president Lincoln\'s given name'},
{word:'COOLIDGE', clue:'30th US president (8)'},
{word:'BUCHANAN', clue:'James _; 15th US president (8)'},
{word:'CARTER', clue:'Jimmy _; US president (6)'},
{word:'ADAMS', clue:'6th US President'},
{word:'KENNEDY', clue:'Assassinated US president (7)'},
{word:'HOOVER', clue:'Herbert --, 31st US President (6)'},
{word:'PIERCE', clue:'Franklin --, 14th US President (6)'},
{word:'KENT', clue:'Daily Planet name'},
{word:'LOIS', clue:'Planet name?'},
{word:'OLSEN', clue:'Daily Planet name'},
{word:'LOIS LANE', clue:'"Daily Planet" employee'},
{word:'EDEN', clue:'"Escape to Chimp ___" (Animal Planet show)'},
{word:'MONGO', clue:'"Flash Gordon" planet of doom'},
{word:'LANE', clue:'"Daily Planet" reporter'},
{word:'VETS', clue:'"Emergency ___" (Animal Planet show)'},
{word:'HUMANS', clue:'"Planet of the Apes" savages'},
{word:'ORK', clue:'"Mork and Mindy" planet'},
{word:'BAHAI', clue:'"One planet" religion'},
{word:'APES', clue:'"Planet of the ---"'},
{word:'BIZARRO', clue:'"Opposite" planet in Superman lore'},
{word:'ICARUS', clue:'"Planet of the Apes" spacecraft'},
{word:'LEWIS', clue:'"Out of the Silent Planet" author'},
{word:'ZAIUS', clue:'"Planet of the Apes" role Dr. ___'},
{word:'APEWORLDISEARTH', clue:'"Planet of the Apes" spoiler'},
{word:'NABOO', clue:'"Star Wars Episode I" planet'},
{word:'OLSON', clue:'"The Daily Planet" cub reporter'},
{word:'RYANS', clue:'"___ Hope" (1975-89 daytime serial)'},
{word:'SONOFZORRO', clue:'1947 swordplay serial'},
{word:'NATIVES', clue:'Adventure serial extras, often'},
{word:'SOAP', clue:'Afternoon serial'},
{word:'CHANREACTION', clue:'Case of the serial investigator?'},
{word:'PERILSOFPAULINE', clue:'Classic silent serial, with "The"'},
{word:'SERIALBROADCASTDRAMA', clue:'Daytime serial'},
{word:'ASTHEWORLDTURNS', clue:'Daytime serial since 1956'},
{word:'SOAP OPERA', clue:'Daytime serial'},
{word:'PARTONE', clue:'First segment of a serial'},
{word:'MARS', clue:'Flash Gordon serial site'},
{word:'NEWER', clue:'Having a higher serial number'},
{word:'PSDCL', clue:'Like a serial'},
{word:'RANK', clue:'Name-serial number connector'},
{word:'SUCCESSIVE', clue:'Serial'},
{word:'NEXUS', clue:'Serial link'},
{word:'CONTINUAL', clue:'Serial'},
{word:'PERIODICAL', clue:'Serial'},
{word:'SICKO', clue:'Serial killer, say'},
{word:'KOALA', clue:'Cuddly Australia animal'},
{word:'EMU', clue:'Speedy animal of Australia'},
{word:'NATIONAL ANTHEMS', clue:'"Advance Australia Fair" et al.'},
{word:'QANTASAIRWAYS', clue:'"The Spirit of Australia" sloganeer'},
{word:'QANTAS', clue:'"The Spirit of Australia" sloganeer'},
{word:'INXS', clue:'80\'s rock band from Australia'},
{word:'CANBERRA', clue:'AUSTRALIA'},
{word:'DOWN UNDER', clue:'AUSTRALIA'},
{word:'ISLANDCONTI', clue:'AUSTRALIA'},
{word:'CONTD', clue:'Africa or Australia: abbr.'},
{word:'FIJI', clue:'Archipelago east of Australia'},
{word:'SPEEDO', clue:'Athletic wear company founded in Australia'},
{word:'ISLANDCONTINENT', clue:'Australia, e.g.'},
{word:'TASMAN SEA', clue:'Australia/New Zealand separator'},
{word:'ADELAIDE', clue:'Big city in Australia'},
{word:'CORAL SEA', clue:'Body of water adjacent to Australia'},
{word:'ZOE', clue:'Caldwell from Australia'},
{word:'PERTH', clue:'Capital city of Western Australia'},
{word:'LORY', clue:'Colorful parrot of Australia'},
{word:'WOMERA', clue:'Dart-hurling weapon of Australia'},
{word:'PLATYPUS', clue:'Egg-laying mammal of Australia'},
{word:'ERAS', clue:'Chapters in world history'},
{word:'REALMS', clue:'World-history topics'},
{word:'SPARTAN', clue:'From the legendary ancient Greek city-state, the ... Army was one of the most disciplined and well-trained military forces in world history'},
{word:'OYSTER', clue:'"... the world\'s mine ___": Shak.'},
{word:'ASTAGE', clue:'"All the world\'s ___"'},
{word:'STAGE', clue:'"All the world\'s a ___"'},
{word:'MARS', clue:'"The War of the Worlds" world'},
{word:'EBAY', clue:'"The World\'s Online Marketplace"'},
{word:'MYERS', clue:'"Wayne\'s World\'s" Mike'},
{word:'FAIR', clue:'"World\'s ___" (E.L. Doctorow novel)'},
{word:'SEATTLE', clue:'1962 World\'s Fair city'},
{word:'UNISPHERE', clue:'1964 World\'s Fair symbol'},
{word:'OSAKA', clue:'1970 World\'s Fair city'},
{word:'OSAKAJAPAN', clue:'1970 World\'s Fair site'},
{word:'SPOKANE', clue:'1974 World\'s Fair site'},
{word:'ASIA', clue:'30% of the world\'s land'},
{word:'MEN', clue:'About half the world\'s population'},
{word:'LAKE', clue:'Baikal is the world\'s deepest'},
{word:'EPCOT', clue:'Disney World\'s ___ Center'},
{word:'NANU', clue:'Disney\'s "World\'s Greatest Athlete"'},
{word:'CYCLORAMA', clue:'Early World\'s Fair attraction'},
{word:'APER', clue:'"Monkey see, monkey do" practitioner'},
{word:'GARYHART', clue:'His monkey business on the Monkey Business got him in trouble'},
{word:'LODE', clue:'"Aladdin" monkey'},
{word:'SCOPES', clue:'"Monkey Trial" defendant of July 1925'},
{word:'LETITBLEED', clue:'"Midnight Rambler," "Gimme Shelter," "Monkey Man"'},
{word:'DURER', clue:'"Virgin with the Monkey" artist'},
{word:'THELMA', clue:'Actress Todd of "Monkey Business"'},
{word:'BABOON', clue:'African monkey'},
{word:'RAMA', clue:'Ally of the monkey king Sugriva'},
{word:'TOTO', clue:'An evil monkey captured him'},
{word:'DORATHEEXPLORER', clue:'Animated friend of the monkey Boots'},
{word:'SEENOEVIL', clue:'Bit of monkey business?'},
{word:'CAPER', clue:'Bit of monkey business'},
{word:'PRANK', clue:'Bit of monkey business'},
{word:'ENGINEER', clue:'Bridge monkey'},
{word:'DESIGNER', clue:'Bridge monkey'},
{word:'TITI', clue:'Capuchin monkey relative'},
{word:'MAHA', clue:'Ceylonese monkey'},
{word:'SHIN', clue:'Climb monkey-style'},
{word:'EPA', clue:'Climbing monkey'},
{word:'CLERK', clue:'Company monkey'},
{word:'OVA', clue:'Largest cells in the human body'},
{word:'STAPES', clue:'Smallest bone in the human body'},
{word:'BONES', clue:'The human body has 206'},
{word:'CADAVER', clue:'Dead human body (7)'},
{word:'SKIN', clue:'Outer layer of the human body'},
{word:'PHYSIQUE', clue:'Constitution of the human body (8)'},
{word:'MIDRIFF', clue:'Part of the human body (7)'},
{word:'TORSO', clue:'Trunk of human body (5)'},
{word:'NUTRIENT', clue:'Fuel for the human body'},
{word:'FEMUR', clue:'Longest bone in the human body'},
{word:'WAIST', clue:'Part of the human body (5)'},
{word:'BLOOD', clue:'It\'s inside every human body'},
{word:'ARM', clue:'Upper limb of the human body'},
{word:'CLAY', clue:'Human body, cold, put down'},
{word:'TOOTHENAMEL', clue:'Hardest substance in the human body'},
{word:'ANATOMY', clue:'Human body (7)'},
{word:'RNA', clue:'Human body molecule'},
{word:'AORTA', clue:'Largest artery in the human body'},
{word:'TOE', clue:'Time keeper of the human body?'},
{word:'MEME', clue:'Viral Internet phenom'},
{word:'MEMES', clue:'Viral Internet images, say'},
{word:'RAIN', clue:'"Chocolate ___" (2007 YouTube viral video)'},
{word:'LAZY', clue:'"___ Sunday" ("SNL" viral video)'},
{word:'SWINE FLU', clue:'Viral disease with farm origins'},
{word:'HERPES', clue:'Viral skin ailment'},
{word:'FLU', clue:'Viral trouble'},
{word:'STRAINS', clue:'Viral varieties'},
{word:'AFRICANFLU', clue:'Viral malady, after continental drift?'},
{word:'IFILM', clue:'Website that archives "viral videos"'},
{word:'YOUTUBE', clue:'Source of a viral outbreak'},
{word:'SWEEPTHECOUNTRY', clue:'Go viral, old-style'},
{word:'GERMAN MEASLES', clue:'Viral illness associated with a rash'},
{word:'SPREADS', clue:'Goes viral, say'},
{word:'DANCINGCAT', clue:'Star of some viral videos'},
{word:'CAT', clue:'Subject of many a viral video'},
{word:'ACHOOS', clue:'Viral chorus?'},
{word:'SPREAD', clue:'Went viral'},
{word:'KONY', clue:'"___ 2012" (viral video)'},
{word:'MEASLES', clue:'An acute infectious viral disease'},
{word:'POLIO', clue:'Viral disease causing paralysis'},


]
