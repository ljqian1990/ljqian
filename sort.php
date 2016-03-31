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
//                  佛祖保佑             永无BUG
//          佛曰:
//                  写字楼里写字间，写字间里程序员；
//                  程序人员写程序，又拿程序换酒钱。
//                  酒醒只在网上坐，酒醉还来网下眠；
//                  酒醉酒醒日复日，网上网下年复年。
//                  但愿老死电脑间，不愿鞠躬老板前；
//                  奔驰宝马贵者趣，公交自行程序员。
//                  别人笑我忒疯癫，我笑自己命太贱；
//                  不见满街漂亮妹，哪个归得程序员？

?>

<script>
var arr = new Array(7,6,5,4,3,2,1);
console.log(arr.sort());
/**
 * 冒泡排序（Bubble Sort，台湾译为：泡沫排序或气泡排序）是一种简单的排序算法。
 * 它重复地走访过要排序的数列，一次比较两个元素，如果他们的顺序错误就把他们交换过来。
 * 走访数列的工作是重复地进行直到没有再需要交换，也就是说该数列已经排序完成。
 * 这个算法的名字由来是因为越小的元素会经由交换慢慢“浮”到数列的顶端。
 *
 * 冒泡排序算法的流程如下：
 * 1、比较相邻的元素。如果第一个比第二个大，就交换他们两个。
 * 2、对每一对相邻元素作同样的工作，从开始第一对到结尾的最后一对。在这一点，最后的元素应该会是最大的数。
 * 3、针对所有的元素重复以上的步骤，除了最后一个。
 * 4、持续每次对越来越少的元素重复上面的步骤，直到没有任何一对数字需要比较。
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
 * 将原问题分解为若干个规模更小但结构与原问题相似的子问题。递归地解这些子问题，然后将这些子问题的解组合为原问题的解。
 *
 * 利用分治法可将快速排序的分为三步：
 * 1、在数据集之中，选择一个元素作为”基准”（pivot）。
 * 2、所有小于”基准”的元素，都移到”基准”的左边；所有大于”基准”的元素，都移到”基准”的右边。这个操作称为分区 (partition) 操作，分区操作结束后，基准元素所处的位置就是最终排序后它的位置。
 * 3、对”基准”左边和右边的两个子集，不断重复第一步和第二步，直到所有子集只剩下一个元素为止。
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
 * 选择排序（Selection Sort）是一种简单直观的排序算法。
 * 它的工作原理如下，首先在未排序序列中找到最小（大）元素，存放到排序序列的起始位置，然后，再从剩余未排序元素中继续寻找最小（大）元素，然后放到已排序序列的末尾。
 * 以此类推，直到所有元素均排序完毕。
 *
 * 选择排序的主要优点与数据移动有关。
 * 如果某个元素位于正确的最终位置上，则它不会被移动。
 * 选择排序每次交换一对元素，它们当中至少有一个将被移到其最终位置上，因此对n个元素的序列进行排序总共进行至多n-1次交换。
 * 在所有的完全依靠交换去移动元素的排序方法中，选择排序属于非常好的一种。
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
 * 二分法插入排序
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
 * 希尔排序算法是按其设计者希尔（Donald Shell）的名字命名，该算法由1959年公布，是插入排序的一种更高效的改进版本。
 * 它的作法不是每次一个元素挨一个元素的比较。
 * 而是初期选用大跨步（增量较大）间隔比较，使记录跳跃式接近它的排序位置；然后增量缩小；最后增量为 1 ，这样记录移动次数大大减少，提高了排序效率。
 * 希尔排序对增量序列的选择没有严格规定。
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
 * 归并排序
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
            if (array[f] >= array[m + 1]) { // 这里使用了插入排序的思想
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
