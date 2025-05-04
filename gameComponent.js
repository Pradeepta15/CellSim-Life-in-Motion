document.addEventListener("DOMContentLoaded", () => {
  const numRows = 25;
  const numCols = 25;

  let running = false;
  let interval;
  let generation = 0;
  let population = 0;

  const generateEmptyGrid = () => Array.from({ length: numRows }, () => Array(numCols).fill(0));
  let grid = generateEmptyGrid();

  const gridContainer = document.getElementById("gridContainer");
  const generationDisplay = document.getElementById("generation");
  const populationDisplay = document.getElementById("population");

  function renderGrid() {
    gridContainer.innerHTML = "";
    gridContainer.style.display = "grid";
    gridContainer.style.gridTemplateColumns = `repeat(${numCols}, 20px)`;

    grid.forEach((row, rIdx) => {
      row.forEach((cell, cIdx) => {
        const cellDiv = document.createElement("div");
        cellDiv.className = `cell ${cell ? "alive" : ""}`;
        cellDiv.addEventListener("click", () => {
          grid[rIdx][cIdx] = grid[rIdx][cIdx] ? 0 : 1;
          renderGrid();
        });
        gridContainer.appendChild(cellDiv);
      });
    });
    updateStats();
  }

  function updateStats() {
    generationDisplay.textContent = generation;
    population = grid.flat().filter(cell => cell).length;
    populationDisplay.textContent = population;
  }

  function nextGeneration() {
    const newGrid = generateEmptyGrid();
    for (let r = 0; r < numRows; r++) {
      for (let c = 0; c < numCols; c++) {
        const neighbors = [
          [r-1, c-1], [r-1, c], [r-1, c+1],
          [r, c-1],             [r, c+1],
          [r+1, c-1], [r+1, c], [r+1, c+1]
        ];
        let alive = 0;
        neighbors.forEach(([x, y]) => {
          if (x >= 0 && x < numRows && y >= 0 && y < numCols) {
            alive += grid[x][y];
          }
        });
        if (grid[r][c] && (alive === 2 || alive === 3)) newGrid[r][c] = 1;
        if (!grid[r][c] && alive === 3) newGrid[r][c] = 1;
      }
    }
    grid = newGrid;
    generation++;
    renderGrid();
  }

  function startGame() {
    if (!running) {
      running = true;
      interval = setInterval(nextGeneration, 500);
    }
  }

  function stopGame() {
    running = false;
    clearInterval(interval);
    saveSession();
  }

  function resetGame() {
    generation = 0;
    population = 0;
    grid = generateEmptyGrid();
    renderGrid();
  }

  function step23() {
    for (let i = 0; i < 23; i++) nextGeneration();
  }

  function loadPattern(pattern) {
    resetGame();
    const midR = Math.floor(numRows / 2);
    const midC = Math.floor(numCols / 2);
    const set = (coords) => coords.forEach(([x, y]) => grid[midR + x][midC + y] = 1);
    const patterns = {
      block: [[0,0],[0,1],[1,0],[1,1]],
      boat: [[0,0],[0,1],[1,0],[1,2],[2,1]],
      beehive: [[0,1],[0,2],[1,0],[1,3],[2,1],[2,2]],
      blinker: [[0,0],[0,1],[0,2]],
      beacon: [[0,0],[0,1],[1,0],[2,3],[3,2],[3,3]],
      glider: [[0,1],[1,2],[2,0],[2,1],[2,2]],
      gosper: [[0,24],[1,22],[1,24],[2,12],[2,13],[2,20],[2,21],[2,34],[2,35],
               [3,11],[3,15],[3,20],[3,21],[3,34],[3,35],[4,0],[4,1],[4,10],[4,16],
               [4,20],[4,21],[5,0],[5,1],[5,10],[5,14],[5,16],[5,17],[5,22],[5,24],
               [6,10],[6,16],[6,24],[7,11],[7,15],[8,12],[8,13]],
      random: Array.from({length: 100}, () => [Math.floor(Math.random() * numRows), Math.floor(Math.random() * numCols)])
    };
    if (patterns[pattern]) set(patterns[pattern]);
    renderGrid();
  }

  function saveSession() {
    fetch("save_session.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        generations: generation,
        duration: generation * 0.5
      })
    });
  }

  renderGrid();

  document.getElementById("start").onclick = startGame;
  document.getElementById("stop").onclick = stopGame;
  document.getElementById("next").onclick = nextGeneration;
  document.getElementById("reset").onclick = resetGame;
  document.getElementById("step23").onclick = step23;
  document.getElementById("patternSelector").onchange = (e) => loadPattern(e.target.value);
});
