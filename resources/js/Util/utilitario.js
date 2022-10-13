import { isNull } from 'lodash';
import { toast } from 'react-toastify';

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

export function getDataFimTrimestralSemestralAnual(dataInicio, monthNumber) {
  var dateStarted = new Date(Date.parse(dataInicio));
  var dateEnded = dateStarted.getTime();
  var termMonths = monthNumber // Pure Month Count

  for (var monthCount = dateStarted.getMonth() + 1; monthCount < dateStarted.getMonth() + (termMonths + 1); monthCount++) {
    dateEnded += (24 * 3600000) * getDaysInMonth(monthCount, dateStarted.getFullYear());
  }
  return formatDate(dateEnded) == "NaN-NaN-NaN" ? "" : formatDate(dateEnded);
}

export function tipoPacote(pacote, tipo) {
  const tipo_pacote = {
    0: {
      1: 3500,
      3: 10500,
      6: 21000,
      12: 40000
    },
    1: {
      1: 6500,
      3: 19500,
      6: 39000,
      12: 75000
    },
    2: {
      1: 12000,
      3: 36000,
      6: 72000,
      12: 144000
    }
  };
  return tipo_pacote[pacote][tipo];
}

export const currency = function (number) {
  return new Intl.NumberFormat('pt-AO', { style: 'currency', currency: 'AOA', minimumFractionDigits: 2 }).format(number);
};

export const numeroNotificacao = function () {
  var not_reg = localStorage.getItem('notificacao_registo');
  var notificacao_registo = isNull(not_reg) || isNaN(not_reg) ? Number.parseInt(0) : Number.parseInt(not_reg);
  var sizeNot = notificacao_registo - Number.parseInt(1);
  return sizeNot < Number.parseInt(0) ? Number.parseInt(0) : sizeNot;
}

export const alertToast = (message, idToast) => {
  toast.info(message, {
    toastId: idToast,
    position: "top-center",
    autoClose: false,
    hideProgressBar: false,
    closeOnClick: true,
  });
}
