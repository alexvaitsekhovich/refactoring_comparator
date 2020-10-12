# Refactoring comparator

[![pipeline statu](https://gitlab.com/alex.vaitsekhovich/refactoring_comparator/badges/main/pipeline.svg)](https://gitlab.com/alex.vaitsekhovich/refactoring_comparator/pipelines) [![Build Status](https://travis-ci.org/alexvaitsekhovich/refactoring_comparator.svg?branch=main)](https://travis-ci.org/alexvaitsekhovich/refactoring_comparator)

[![codecov](https://codecov.io/gh/alexvaitsekhovich/refactoring_comparator/branch/main/graph/badge.svg)](https://codecov.io/gh/alexvaitsekhovich/refactoring_comparator) [![Codacy Badge](https://api.codacy.com/project/badge/Grade/6c5df06d3af8432c8547fd774bef14f5)](https://app.codacy.com/gh/alexvaitsekhovich/refactoring_comparator?utm_source=github.com&utm_medium=referral&utm_content=alexvaitsekhovich/refactoring_comparator&utm_campaign=Badge_Grade) [![Maintainability](https://api.codeclimate.com/v1/badges/4cd20d5cc4cc11c47998/maintainability)](https://codeclimate.com/github/alexvaitsekhovich/refactoring_comparator/maintainability)


## Refactoring problem ## 

Legacy code can be a black box for developers - monolithic architecture, without tests, with unclear logic and language constructs. If the authors of the code left the company and are not available for questions, it becomes impossible to extend or even maintain the application. The only thing we can rely on - the code still runs and produces correct output into the database.

Obviously we must refactor the code or write a new application. But how can we be sure that out new application will deliver the same results into the database? At the end, that's the only think our stakeholders care about.

## Solution - refactoring comparator ## 

This simple concept can help us on our way to the new better application:
1. Create two identical databases - for legacy code and for the refactored application
2. Populate the databases with same data
3. Run both applications with identical arguments
4. Compare the data in both databases, ignoring some irrelevant columns, like creation date.

From this idea the refactoring comparator was created.

## How to use it ## 

Configure the comparator:

_config/application.ini_

```
scriptArgs.scriptCommandLineArgs - command line arguments for both applications
comparison.limitDifferencesPerTable - if mismatch will be found, show only this amount of differences per table
db.excludedColumns - columns that should be excluded form comparison, comma-separated list of columns for every table
datasource.dataSourceImpl - implementation of the datasource reader, getting data form the database
datasource.dataSourceConf - configuration of the dataSourceImpl class, fixture data can be defined there
datasource.dataFixtureImpl - implementation of the fixture interface, populating the database
```

_config/db.ini_

```
legacydb - configuration for the legacy database
refactoreddb - configuration for the new database
```

_config/scripts.ini_

```
legacy and refactored scripts are defined here
```


<p align="center">
<img src="https://github.com/alexvaitsekhovich/images/blob/main/rcomparator.png" width="90%" height="90%" alt="Speed graph">
