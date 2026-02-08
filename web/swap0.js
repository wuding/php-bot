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


  // a. 时间

  ele('execute_time').value = get_startTime()


  // b. 缓存

  url = url || ele('url').value
  console.log('url = '+ url)
  // 同一地址非连续请求无次数限制
  if (lastUrl != url) {
    REQ[url] = -1
    lastUrl = url
  }



  // 计数
  if (!REQ[url]) {
    REQ[url] = 1
  } else {
    REQ[url]++
  }

  // c. 请求限制

  console.log('frequency = '+ frequency)
  if (frequency < REQ[url]) {
    message('max request times '+ frequency)
    return false
  } else {
    timeoutApi = 100 + REQ[url] * 100
  }


  // d. timeoutId

  var xhr = getTime()
  clearTimeout(timeoutId)
  var timeoutId = setTimeout("apiAgain('" + url + "', " + xhr + ")", timeoutMs)


  // e.

  readyArr = [1, 2, 3]
  statusArr = [0, 502, 504]
  XHR[xhr] = x = new XMLHttpRequest()
  x.onreadystatechange = function() {
    clearTimeout(timeoutId)
      readySt = x.readyState
      if (4 == readySt) {
          if (200 == x.status) {
              text = x.responseText
              if (text) {
                  eval("json = " + text + "; apiCall(json, '" + xhr + "');")
              } else {
                  message('no response text ('+ xhr +') : '+  url)
                  apiAgain(url, xhr)
              }
              ele('result_code').innerText = text

          } else if (statusArr.includes(x.status)) {
              apiAgain(url, xhr)

          } else {
              statusMsg(x, 'Problem retrieving data')
          }

      } else if (!readyArr.includes(readySt)) {
          statusMsg(x, 'readyState: '+ readySt +' Problem status')
      }
  }
  x.open('GET', url, true)
  x.send(null)

}


// c. XHR 回调
function apiCall(json, func) {
  console.log(312 +'= apiCall('+ json +', '+ func +')')
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
