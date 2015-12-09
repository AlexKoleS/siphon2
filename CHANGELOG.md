# Siphon Changelog

### Next Version (prediction: 3.0.0)

* **[BC]** Changed to asynchronous/promise-based implementation
* **[BC, IMPROVED]** Added team information to player statistics in `BoxScoreResponse`
* **[NEW]** Added `StatisticsCollection::findGroupByKey()`


### 2.0.0 (2015-10-15)

* **[BC, FIXED]** Allow for missing URLs in image feeds

### 1.1.1 (2015-09-22)

* **[FIXED]** Added "Six-Game Injury List" to injury status enumeration

### 1.1.0 (2015-09-15)

* **[NEW]** Added support for team statistics

### 1.0.0 (2015-07-22)

* **[NEW]** Added `isFinalized` flag to `BoxScoreResponse`
* **[FIXED]** Wrapped Guzzle XML parse exceptions in Siphon `ServiceUnavailable` exception
* **[FIXED]** Corrected documentation and type-hints on `PlayerStatisticsResponse` constructor

### 0.2.0 (2015-06-16)

* **[BC]** Rewrote the entire library to use a request/response model.

### 0.1.1 (2015-03-20)

* **[FIXED]** Added missing statuses to `ScopeStatus`

### 0.1.0 (2015-03-16)

* Initial release
