#include <iostream>
#include <bits/stdc++.h>
#include "opencv2/core/core.hpp"
#include "opencv2/highgui/highgui.hpp"
#include "opencv2/opencv.hpp"
#include "opencv2/imgproc/imgproc.hpp"
#include <opencv2/objdetect/objdetect.hpp>

using namespace cv; 
using namespace std; 

int main()
{
	//Mat I1 = imread("1.jpg");
	//Mat I1(3,3);
	//Mat I1(2,2, CV_8UC3)={1,3,4,5};
	 Mat I1 = (Mat_<double>(5,5) << 1, 3, 4, 5);
	cout<<I1<<endl<<endl;
	cout<<I1.rows<<"\t"<<I1.cols<<endl;
	int radius = 1;
	Mat I2;
	copyMakeBorder(I1, I2, radius, radius, 1, 1, BORDER_CONSTANT);
	cout<<I2.rows<<"\t"<<I2.cols<<endl;
	cout<<I2<<endl<<endl;
	return 0;
}