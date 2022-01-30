function getDaysInMonth(month, year) {
  return new Date(year, month, 0).getDate();
}

function formatDate(date) {
  var d = new Date(date),
      month = '' + (d.getMonth() + 1),
      day = '' + d.getDate(),
      year = d.getFullYear();

  if (month.length < 2)
      month = '0' + month;
  if (day.length < 2)
      day = '0' + day;

  return [year, month, day].join('-');
}

export function getDataFimTrimestralSemestralAnual(dataInicio, monthNumber){
  var dateStarted = new Date(Date.parse(dataInicio));
  var dateEnded = dateStarted.getTime();
  var termMonths = monthNumber // Pure Month Count

  for(var monthCount = dateStarted.getMonth() + 1; monthCount < dateStarted.getMonth() + (termMonths + 1); monthCount++) {
    dateEnded += (24*3600000) * getDaysInMonth(monthCount, dateStarted.getFullYear());
  }
  return formatDate(dateEnded);
}
