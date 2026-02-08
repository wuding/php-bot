// version 1.260208

/*
0. define
*/

// a. 时间
firstTime = getTime()
lastTime = getTime()

// b. 整数定义
step = 0
stop = 0
frequency = 3
timeoutApi = 100
offsetTime = 0
timeoutMs = 300000
timeoutVal = 1

// c. 可未定义
lastUrl = null
timeoutId = null

// 数组定义，连等会合并键名
REQ = []
XHR = []
LOG = []






/*
1. 通用
*/

// a. 简化
function ele(id) {
    return document.getElementById(id)
}

function num(id) {
    return parseInt(ele(id).value)
}

// b. 列表操作
function selectChange(obj) {
    start(1)
    ele('item').value = itm = log.options[ log.selectedIndex ].text
    ele('url').value = obj.value
    recovery(0, itm)
}

// c. 格式化时间
function timeFormat(d) {
    return tm = d.getHours() +':'+ d.getMinutes() +':'+ d.getSeconds() +'.'+ d.getMilliseconds()
}


// 毫秒格式化为秒 直接除 1000 啊
function secondFormat(time) {
    time = new String(time)
    sec = 0
    ms = time
    if (3 < time.length) {
        len = time.length - 3
        sec = time.substring(0, len)
        ms = time.substring(len)
    }
    return sec + '.'+ ms
}


/*
2. 项目
*/

// 把网址转为名称
function setItemName(url) {
    url = url.trim()
    search = url.search(/\?/)
    itm = url.substring(0, search)
    itm = itm.replace(/^http:\/+/gi, '')
    itm = itm.replace(/^\/+/gi, '')
    itm = itm.replace(/:\/+/gi, '.')
    itm = itm.replace(/\//gi, '.')
    ele('item').value = itm
    return itm
}

function record(nm) {
console.log(89 +'record('+ nm +')')
    obj = {
        url: ele('url').value,
        init_url: ele('init_url').value,
        delay: ele('timeout').value,
        maximum: ele('max_page').value,
        interval: ele('millisec').value,
        repeat: ele('repeat_time').value,
        code: ele('code').value
    }
    //console.log(obj)
    json = JSON.stringify(obj)
    localStorage.setItem(nm, json)

    if (!ele('init_url').value) {
        ele('init_url').value = ele('url').value
    }
}

function localLogOpt(value, index, array)
{
  console.log(89 +'localLogOpt('+ value +','+ index +','+ array +')')
    obj = LOG[index]
    url = obj.url

    opt = document.createElement("option")
    opt.innerHTML = value
    opt.setAttribute('value', url)

    log = ele('request_log')
    console.log(log);
    try {
        log.append(opt)
    } catch (err) {
        console.log(err)
    }
}

function localLog()
{
    keys = []
    for (i = 0; i < localStorage.length; i++) {
        key = localStorage.key(i)
        val = localStorage.getItem(key)
        regex = /^\{/i
        result = regex.test(val)
        if (!result) {
            continue
        }

        obj = JSON.parse(val)
        LOG.push(obj)
        keys.push(key)
    }
    keys.sort()
    keys.forEach(localLogOpt)
}

// 从项目恢复
function recovery(c, itm) {
    item = itm || ele('item').value
    if (item) {
        json = localStorage.getItem(item)
        obj = JSON.parse(json)
        console.log(obj)
        wayback(obj)
    }
    if (c) {
        start()
    }
}

function wayback(obj) {
    ele('url').value = obj.url
    ele('timeout').value = obj.delay
    ele('max_page').value = obj.maximum
    ele('millisec').value = obj.interval
    ele('repeat_time').value = obj.repeat
    ele('code').value = obj.code
    ele('init_url').value = obj.init_url
}

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

  var xhr = lastTime = getTime()
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


  // a. err
  if (!json) {
    message('apiCall ERROR')
    return false
  }

  // b. type
  type = Object.prototype.toString.call(json)
  if ("[object Object]" != type) {
    message('api json type: '+ type)
    return false
  }

  // c-. skip
  info = json.info
  skip = info?.skip
  if (skip) {
      ele('result_log').innerText = ele('result_log').innerText +"\r\n"+ ele('url').value +"\r\n"+ skip;
  }

  // c. 统计次数
  ele('requests').value = 1 + new Number(ele('requests').value)

  // d. 偏移量
  var d = new Date()
  var nowTime = d.getTime()
  var offset = 0

  if (offsetTime) {
    offset = parseInt(nowTime) - parseInt(offsetTime)
    message('offset : '+ offset)
    if (!stop) {
      offsetTime = 0
    }
  }

  // e. 统计用时
  var useTime = parseInt(nowTime) - parseInt(firstTime) - offset;
  var uTime = useTime / 1000

  console.log([nowTime, firstTime, offset, uTime])
  ele('use_time').value = uTime
  ele('avg_time').value = uTime / num('requests')

  // f.
  var item = ele('item').value.trim()
  var urlMsg = urlPre = url = ele('url').value
  // 异常
  if (json.code) {
    var itm = item || setItemName(url)
    record(itm)
    message('api json msg: '+ json.msg)
    start()

  } else if (!stop) {
    api_call_fn0(urlMsg, urlPre, nowTime, json)
  }

}


function api_call_fn0(urlMsg, urlPre, nowTime, json)
{
  console.log(312 +'= api_call_fn0('+ urlMsg +', '+ urlPre +', '+ nowTime +', '+ json +')')
  var pageNo = 0
  var urlJson = json.msg
  if (urlJson) {
    search = urlJson.trim().search(/^(http|\/)/i)
    if (-1 == search) {
      urlJson = ''
    }
  }

  urlMsg = urlJson || urlMsg
  var search = urlMsg.search(/\?/)
  var searchStr = ''

  if (-1 == search) {
    var prefix = urlMsg
  } else {
    api_call_fn1(pageNo, urlMsg, urlPre, urlJson, nowTime, search, searchStr)

  }
}

function api_call_fn1(pageNo, urlMsg, urlPre, urlJson, nowTime, search, searchStr)
{
  console.log(312 +'= api_call_fn1('+ pageNo +', '+ urlMsg +', '+ urlPre +', '+ urlJson +', '+ nowTime +', '+ search +', '+ searchStr +')')
  var prefix = urlMsg.substr(0, search) + '?'
  var substr = urlMsg.substr(search + 1)
  var split = substr.split(/&/)
  for (i = 0; i < split.length; i++) {
    var srch = split[i].search(/=/)
    var name = split[i].substring(0, srch)
    var val = split[i].substr(srch + 1)

    if ('page' == name) {
      pageNo = parseInt(val)
      ele('last_time').value = tm = get_startTime()
      timeOver = parseInt(nowTime) - parseInt(lastTime)

      ele('last_use').value = sm = secondFormat(timeOver)
      document.title = pageNo +'('+ sm +')'+ tm
      to = timeOver / 1000
      useReal = new Number(ele('use_real').value)
      uReal = to + useReal
      ele('use_real').value = uReal
      ele('avg_real').value = uReal / num('requests')
      // break

      if (!urlJson) {
      pageNo = val = pageNo + 1
      }
    }

    if (name) {
      searchStr += '&'+ name +'='+ val
    }
  }
  searchStr = searchStr.replace(/^&/, '')

  api_call_fn2(json, pageNo, prefix, searchStr, urlPre)
}


function api_call_fn2(json, pageNo, prefix, searchStr, urlPre)
{
  console.log(312 +'= api_call_fn2('+ json +', '+ pageNo +', '+ prefix +', '+ searchStr +', '+ urlPre +')')
  // 页数限制
  var maxPage = num('max_page') || json.data.pageCount
  inPage = 1
  if (json.data.lastTime || (maxPage && pageNo > maxPage)) {
    inPage = 0
    start(1)
  }

  if (!inPage) {
    message('pause')
    return false
  }

  var urlInf = urlInfo = json.msg || prefix + searchStr
  if (urlPre != urlInfo) {
    message('url previous')
    // return false
  }

  if (urlInfo && 'final' != urlInfo) {
    if (json.data.timeout) {
      ele('timeout').value = json.data.timeout
    }


    timeoutVal = ele('timeout').value
    if (!timeoutVal) {
      timeoutVal = 100
    }
    timeoutVal = parseInt(timeoutVal) + timeoutApi


    var timeoutUrl = '-'
    var urlPrefix = urlInfo.trim().search(/^(http|\/)/i)
    if (-1 == urlPrefix) {
      urlInfo = urlInf
    }
    ele('url').value = urlInfo
    timeoutUrl = setTimeout("api('"+ urlInfo +"')", timeoutVal)

    message('t' + timeoutUrl + ' urlPrefix ' + urlPrefix + ' ' + urlInfo, 'note')

    var item = ele('item').value.trim()
    if (item) {
      record(item)
    } else {
      setItemName(urlInfo)
    }

  }
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

// 消息
function message(msg, id) {
  // console.log(540 +'message('+ msg +','+ id +')')
  id = id || 'msg'
  ele(id).innerHTML = msg
}








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



