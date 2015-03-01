(function () {
/**
 * Towers of Hanoi is from The HTML5 Developers Cookbook by Leadbetter and Hudson
 * Firstly i will take the px and move them to % and em for the Grid, later maybe
 * i will continue the game :-) My first game was 2^n + 8, means 24 moves because
 * i made one mistake. I think i found how to move i one sentence but have to play
 * again to remember, i could see the proof already in MIT´s 6.042J "Math for CS".
 */
var towers = [[],[],[]];
var numBlocks = 4;
var numMoves = 0;
function initTowers () {
    var towerDropZones = document.querySelectorAll('#towers .towerDropZone');
    [].forEach.call(towerDropZones, function (tdz) {
        tdz.addEventListener('dragover', towerHandleDragOver, false);
        tdz.addEventListener('drop', towerHandleDragDrop, false);
        tdz.addEventListener('dragleave', towerHandleDragLeave, false);
    });
    var blocks = document.querySelectorAll('.block');
    [].forEach.call(blocks, function (block) {
        block.addEventListener('dragstart', blockHandleDragStart, false);
        block.addEventListener('dragend', blockHandleDragEnd, false);
    });
    for (var i=numBlocks-1;i>=0;i--) {
        towers[0].push(i);
        document.getElementById(i+'block').style.width = (90 + i * 30) + 'px';
    }
}
function towerHandleDragOver(e) {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'move';
    this.className = 'towerDropZone over';
    return false;
}
function towerHandleDragLeave(e) {
    this.className = 'towerDropZone';
}
function towerHandleDragDrop(e) {
    e.preventDefault();
    this.className = 'towerDropZone';
    var blockInfo = JSON.parse(e.dataTransfer.getData('Text'));
    var blockId = blockInfo.blockId;
    var blockNum = parseInt(blockInfo.blockId);
    var fromTowerId = parseInt(blockInfo.fromTowerId);
    var toTowerId = this.id;
    var tower = document.getElementById('tower'+toTowerId);
    var towerHeight = towers[toTowerId].length;
    if (towerHeight == 0) {
        tower.insertBefore(document.getElementById(blockId), document.getElementById('p'+toTowerId));
    } else {
        var topBlock = towers[toTowerId][towerHeight-1];
        if (topBlock > blockNum) {
            tower.insertBefore(document.getElementById(blockId), document.getElementById(topBlock+'block'));
        } else {
            return false;
        }
    }
    towers[toTowerId].push(blockNum);
    towers[fromTowerId].pop();
    numMoves++;
    document.getElementById('numMoves').textContent = numMoves;
    if (towers[2].length == numBlocks) {
        var blocks = document.querySelectorAll('.block');
        [].forEach.call(blocks, function (block) {
            block.draggable = false;
            alert('Congratulations, you have moved the tower.');
        });
    }
}
function blockHandleDragStart(e) {
    var blockId = this.id;
    var fromTowerId = this.parentNode.parentNode.id;
    var tower = towers[fromTowerId];
    var towerHeight= tower.length;
    var topBlock = tower[towerHeight-1];
    var thisBlock = parseInt(blockId);
    if (topBlock == thisBlock) {
        this.style.opacity = '0.4';
        var blockInfo = {
            'blockId': blockId,
            'fromTowerId': fromTowerId
        };
        e.dataTransfer.setData('Text', JSON.stringify(blockInfo));
    } else {
        this.style.opacity = '1.0';
        return false;
    }
}
function blockHandleDragEnd(e) {
    this.style.opacity='1.0';
}
window.addEventListener('load', initTowers, false);

}());