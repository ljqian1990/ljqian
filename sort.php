 <?php 
//                            _ooOoo_
//                           o8888888o
//                           88" . "88
//                           (| -_- |)
//                            O\ = /O
//                        ____/`---'\____
//                      .   ' \\| |// `.
//                       / \\||| : |||// \
//                     / _||||| -:- |||||- \
//                       | | \\\ - /// | |
//                     | \_| ''\---/'' | |
//                      \ .-\__ `-` ___/-. /
//                   ___`. .' /--.--\ `. . __
//                ."" '< `.___\_<|>_/___.' >'"".
//               | | : `- \`.;`\ _ /`;.`/ - ` : | |
//                 \ \ `-. \_ __\ /__ _/ .-` / /
//         ======`-.____`-.___\_____/___.-`____.-'======
//                            `=---='
//
//         .............................................
//                  ���汣��             ����BUG
//          ��Ի:
//                  д��¥��д�ּ䣬д�ּ������Ա��
//                  ������Աд�������ó��򻻾�Ǯ��
//                  ����ֻ���������������������ߣ�
//                  ��������ո��գ����������긴�ꡣ
//                  ��Ը�������Լ䣬��Ը�Ϲ��ϰ�ǰ��
//                  ���۱������Ȥ���������г���Ա��
//                  ����Ц��߯��񲣬��Ц�Լ���̫����
//                  ��������Ư���ã��ĸ���ó���Ա��

?>

<script>
var arr = new Array(7,6,5,4,3,2,1);
console.log(arr.sort());
/**
 * ð������Bubble Sort��̨����Ϊ����ĭ���������������һ�ּ򵥵������㷨��
 * ���ظ����߷ù�Ҫ��������У�һ�αȽ�����Ԫ�أ�������ǵ�˳�����Ͱ����ǽ���������
 * �߷����еĹ������ظ��ؽ���ֱ��û������Ҫ������Ҳ����˵�������Ѿ�������ɡ�
 * ����㷨��������������ΪԽС��Ԫ�ػᾭ�ɽ������������������еĶ��ˡ�
 *
 * ð�������㷨���������£�
 * 1���Ƚ����ڵ�Ԫ�ء������һ���ȵڶ����󣬾ͽ�������������
 * 2����ÿһ������Ԫ����ͬ���Ĺ������ӿ�ʼ��һ�Ե���β�����һ�ԡ�����һ�㣬����Ԫ��Ӧ�û�����������
 * 3��������е�Ԫ���ظ����ϵĲ��裬�������һ����
 * 4������ÿ�ζ�Խ��Խ�ٵ�Ԫ���ظ�����Ĳ��裬ֱ��û���κ�һ��������Ҫ�Ƚϡ�
 */
function bubble(arr){
	var length = arr.length;
	var i,j;
	for(i=length-1; i>0; i--){
		for(j=0; j<i; j++){
			if(arr[j]>arr[j+1]){
				var tmp = arr[j+1];
				arr[j+1] = arr[j];
				arr[j] = tmp;
			}
			console.log(arr);
		}
	}
	return arr;
}


// bubble(arr);

/**
 * ��ԭ����ֽ�Ϊ���ɸ���ģ��С���ṹ��ԭ�������Ƶ������⡣�ݹ�ؽ���Щ�����⣬Ȼ����Щ������Ľ����Ϊԭ����Ľ⡣
 *
 * ���÷��η��ɽ���������ķ�Ϊ������
 * 1�������ݼ�֮�У�ѡ��һ��Ԫ����Ϊ����׼����pivot����
 * 2������С�ڡ���׼����Ԫ�أ����Ƶ�����׼������ߣ����д��ڡ���׼����Ԫ�أ����Ƶ�����׼�����ұߡ����������Ϊ���� (partition) �������������������󣬻�׼Ԫ��������λ�þ����������������λ�á�
 * 3���ԡ���׼����ߺ��ұߵ������Ӽ��������ظ���һ���͵ڶ�����ֱ�������Ӽ�ֻʣ��һ��Ԫ��Ϊֹ��
 */
function quickSort(arr){
	function swap(arr, i, k){
		var tmp = arr[i];
		arr[i] = arr[k];
		arr[k] = tmp;
	}

	function partition(arr, left, right){
		var storeIndex = left;
		var pivot = arr[right];
		for(var i=left; i<right; i++){
			if(arr[i] < pivot){
				swap(arr, i, storeIndex);
				storeIndex+=1;
			}
		}
		swap(arr, storeIndex, right);
		return storeIndex;
	}

	function sort(arr, left, right){
		if(left > right){
			return;
		}
		var storeIndex = partition(arr, left, right);
		sort(arr, left, storeIndex-1);
		sort(arr, storeIndex+1, right);
	}

	sort(arr, 0, arr.length-1);
	return arr;
}

/**
 * ѡ������Selection Sort����һ�ּ�ֱ�۵������㷨��
 * ���Ĺ���ԭ�����£�������δ�����������ҵ���С����Ԫ�أ���ŵ��������е���ʼλ�ã�Ȼ���ٴ�ʣ��δ����Ԫ���м���Ѱ����С����Ԫ�أ�Ȼ��ŵ����������е�ĩβ��
 * �Դ����ƣ�ֱ������Ԫ�ؾ�������ϡ�
 *
 * ѡ���������Ҫ�ŵ��������ƶ��йء�
 * ���ĳ��Ԫ��λ����ȷ������λ���ϣ��������ᱻ�ƶ���
 * ѡ������ÿ�ν���һ��Ԫ�أ����ǵ���������һ�������Ƶ�������λ���ϣ���˶�n��Ԫ�ص����н��������ܹ���������n-1�ν�����
 * �����е���ȫ��������ȥ�ƶ�Ԫ�ص����򷽷��У�ѡ���������ڷǳ��õ�һ�֡�
 */
function selectionSort(arr){
	var length = arr.length;
	var minIndex,minValue,tmp;
	for(var i=0; i<length-1; i++){
		minIndex = i;
		minValue = arr[minIndex];
		for(var j=i+1; j<length; j++){
			if(arr[j] < minValue){
				minIndex = j;
				minValue = arr[j];
			}
		}
		tmp = arr[i];
		arr[i] = minValue;
		arr[minIndex] = tmp;
	}
	return arr;
}

function heapSort(arr){
	function maxHeapify(arr, index, heapSize){
		var iMax = index;
		var iLeft = 2*index+1;
		var iRight = 2*(index+1);

		if(iLeft < heapSize && arr[iLeft] > arr[iMax]){
			iMax = iLeft;
		}

		if(iRight < heapSize && arr[iRight] > arr[iMax]){
			iMax = iRight;
		}

		if(iMax != index){
			swap(arr, iMax, index);
			maxHeapify(arr, iMax, heapSize);
		}
	}

	function swap(arr, i, k){
		var tmp = arr[i];
		arr[i] = arr[k];
		arr[k] = tmp;
	}

	function buildMaxHeap(arr, heapSize){
		var iParent = Math.floor((heapSize-1)/2);

		for(var i=iParent; i>=0; i--){
			maxHeapify(arr, i, heapSize);
		}
	}

	function sort(arr){
		buildMaxHeap(arr);

		for(var i=arr.length-1; i>0; i--){
			swap(arr, 0, i);
			maxHeapify(arr, 0, i);
		}
		return arr;
	}

	return sort(arr);
}

// console.log(heapSort(arr));


/**
 * ���ַ���������
 */
function insertionSort2(array) {
	function binarySearch(array, start, end, temp) {
    	var middle;
    	while (start <= end) {
      		middle = Math.floor((start + end) / 2);
      		if (array[middle] < temp) {
        		if (temp <= array[middle + 1]) {
          			return middle + 1;
        		} else {
          			start = middle + 1;
        		}
      		} else {
        		if (end === 0) {
          			return 0;
        		} else {
          			end = middle;
        		}
      		}
    	}
	}

	function binarySort(array) {
    	var length = array.length,
        	i,
        	j,
        	k,
        	temp;
    	for (i = 1; i < length; i++) {
      		temp = array[i];
      		if (array[i - 1] <= temp) {
        		k = i;
      		} else {
        		k = binarySearch(array, 0, i - 1, temp);
        		for (j = i; j > k; j--) {
          			array[j] = array[j - 1];
        		}
      		}
      		array[k] = temp;
    	}
    	return array;
  	}
 	return binarySort(array);
}


/**
 * ϣ�������㷨�ǰ��������ϣ����Donald Shell�����������������㷨��1959�깫�����ǲ��������һ�ָ���Ч�ĸĽ��汾��
 * ������������ÿ��һ��Ԫ�ذ�һ��Ԫ�صıȽϡ�
 * ���ǳ���ѡ�ô�粽�������ϴ󣩼���Ƚϣ�ʹ��¼��Ծʽ�ӽ���������λ�ã�Ȼ��������С���������Ϊ 1 ��������¼�ƶ����������٣����������Ч�ʡ�
 * ϣ��������������е�ѡ��û���ϸ�涨��
 */
function shellSort(arr) {

	function swap(arr, i, j){
		var tmp = arr[i];
		arr[i] = arr[j];
		arr[j] = tmp;
	}

	var length = arr.length;
	var gap = Math.floor(length/2);

	while(gap > 0){
		for(var i=gap; i<length; i++){
			for(var j=i; j>0; j-=gap){
				if(arr[j-gap] > arr[j]){
					swap(arr, j-gap, j);
				}else{
					break;
				}
			}
		}
		gap = Math.floor(gap/2);
	}
	return arr;
}

// console.log(shellSort(arr));

/**
 * �鲢����
 */
function mergeSort(array) {

    function sort(array, first, last) {
        first = (first === undefined) ? 0 : first
        last = (last === undefined) ? array.length - 1 : last
        if (last - first < 1) {
            return;
        }
        var middle = Math.floor((first + last) / 2);
        sort(array, first, middle);
        sort(array, middle + 1, last);

        var f = first,
            m = middle,
            i,
            temp;

        while (f <= m && m + 1 <= last) {
            if (array[f] >= array[m + 1]) { // ����ʹ���˲��������˼��
                temp = array[m + 1];
                for (i = m; i >= f; i--) {
                    array[i + 1] = array[i];
                }
                array[f] = temp;
                m++
            } else {
                f++
            }
        }

        return array;
    }

    return sort(array);
}




</script>
