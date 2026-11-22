UPDATE hero_mastery
SET displayLevel = NULL
WHERE displayLevel IS NOT NULL
  AND displayLevel >= (
    (qualifyingGames >= 5) + (qualifyingGames >= 10) + (qualifyingGames >= 15) +
    (qualifyingGames >= 25) + (qualifyingGames >= 35) + (qualifyingGames >= 50) +
    (qualifyingGames >= 75) + (qualifyingGames >= 100) + (qualifyingGames >= 150) +
    (qualifyingGames >= 200) + (qualifyingGames >= 250) + (qualifyingGames >= 350) +
    (qualifyingGames >= 500) + (qualifyingGames >= 750) + (qualifyingGames >= 1000)
  );
