// version 1.260208

/*
0. define
*/

// a. 时间
firstTime = getTime()

// b. 整数定义
step = 0
stop = 0
frequency = 30
timeoutApi = 100
offsetTime = 0
timeoutMs = 300000

// c. 可未定义
lastUrl = null
timeoutId = null

// 数组定义，连等会合并键名
REQ = []
XHR = []







/*
1. 通用
*/

// a. 简化
function ele(id) {
    return document.getElementById(id)
}

// c. 格式化时间
function timeFormat(d) {
    return tm = d.getHours() +':'+ d.getMinutes() +':'+ d.getSeconds() +'.'+ d.getMilliseconds()
}





/*
2. 项目
*/


/*
3. API
*/

// a. XHR 执行
function api(url)
{
  console.log(94 +'= api('+ url +')')
}










/*
4. 操作
*/

/* a. request */
// url
// 开始
function start(s)
{
  console.log(37 +'= start('+ s +')')
  // a.
  // 保持进行中
  if (0 === s) {
    step = 0
  }

  // b.
  var btn = ele('btn-request')
  if (s || 1 == step) {
    btn.innerHTML = '继续'

    pause()
    step = 0

    offsetTime = getTime()

  } else if (!step) {
    btn.innerHTML = '暂停'
    ele('start_time').value = get_startTime()

    stop = 0
    api()
    step = 1
  }

  // c.
  document.title = get_startTime() +'('+ offsetTime +')'
}

// 暂停
function pause()
{
  console.log(71 +'= pause()')
  stop = 1
  step = 0
}





/*
5. 任务
*/

/*
6. 信息
*/










/*
7. function
*/
function get_startTime()
{
  var dt = new Date()
  return timeFormat(dt)
}

function getTime()
{
  var d = new Date()
  return d.getTime()
}
